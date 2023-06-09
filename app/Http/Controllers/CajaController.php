<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCajaRequest;
use App\Http\Requests\UpdateCajaRequest;
use App\Models\Caja;
use App\Models\AperturaCaja;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CajaController extends Controller{
    private $c_reg_panel = 25;
    private $c_reg_lista = 10;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($pag=0){
        $c_paginas = ceil(Caja::count()/$this->c_reg_panel);
        $salto = $pag*$this->c_reg_panel;

        $query = Caja::select("id","descripcion","saldo_actual");
        // if($busqueda !=""){
        //     $query = $query->where("usuario.nombre_usuario","like",$busqueda)->orWhere("usuario.nombre","like",$busqueda)->orWhere("usuario.apellido","like",$busqueda)->orWhere("usuario.apellido","like",$busqueda);
        // }
        $query = $query->orderBy("descripcion");

        return ["cod"=>"00","msg"=>"todo correcto","pagina_actual"=>$pag,"cantidad_paginas"=>$c_paginas,"datos"=>$query->get()];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCajaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCajaRequest $request){
        try {
            $campos = $this->validate($request,[
                "descripcion"=>"required|string",
                "pin"=>"required|string|max:4"
            ]);
            $campos['saldo_actual']="0";
            $caja = Caja::create($campos);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return ["cod"=>"06","msg"=>"Error al insertar los datos","errores"=>[$e->errors() ]];

        } catch (\Exception $e) {
            return ["cod"=>"05","msg"=>"Error al insertar los datos","error"=>$e->getMessage()];
        }
        return ["cod"=>"00","msg"=>"todo correcto"];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Caja  $caja
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        try {
            $caja = Caja::findOrfail($id);
            return ["cod"=>"00","msg"=>"todo correcto","datos"=>[$caja]];
        } catch( ModelNotFoundException $e){
            return ["cod"=>"04","msg"=>"no existen datos","error"=>$e->getMessage()];
        } catch (\Exception $e) {
            return ["cod"=>"99","msg"=>"Error general","error"=>$e->getMessage()];
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Caja  $caja
     * @return \Illuminate\Http\Response
     */
    public function edit(Caja $caja){
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCajaRequest  $request
     * @param  \App\Models\Caja  $caja
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCajaRequest $request, $id){
        try {
            $barrio = Caja::findOrfail($id);
            $campos = $this->validate($request,[
                "descripcion"=>"required|string",
                "pin"=>"required|string|max:4"
            ]);

            $barrio->update($campos);
            return ["cod"=>"00","msg"=>"todo correcto"];

        } catch (\Illuminate\Validation\ValidationException $e) {
            return ["cod"=>"06","msg"=>"Error al insertar los datos","errores"=>[$e->errors() ]];

        } catch (\Exception $e) {
            return ["cod"=>"05","msg"=>"Error al insertar los datos","error"=>$e->getMessage()];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Caja  $caja
     * @return \Illuminate\Http\Response
     */
    public function destroy(Caja $caja){ //VERIFICAR SOFT DELETES
        //
    }

    public function abrirCaja(UpdateCajaRequest $request,$id){
        try {
            \date_default_timezone_set('America/Santiago');
            $date = \date('Y-m-d h:i:s a', \time());
            $caja = Caja::findOrfail($id);
            // $caja->estadoCaja;
            $estado = $caja->estadoCaja->last();

            if($estado!= null  && $caja->estadoCaja->last()->estado ){
                return ["cod"=>"11","msg"=>"Caja ya abierta","ultimo"=>$estado];

            }


            $campos = $this->validate($request,[
                "saldo"=>"required|integer",
                "pin"=>"required|string",
            ]);
            // validar pin
            // if($request->input('pin') == 'xx'){
            //     return ["cod"=>"12","msg"=>"Pin incorrecto para la caja"];
            // }
            $aperturaData = [
                'usuario_id'=>'1',
                'saldo_apertura'=>$campos['saldo'],
                'fecha_apertura'=>$date,
                'estado'=>1
            ];
            $caja->update(['saldo_actual'=>$campos['saldo']]);
            $apertura = new AperturaCaja($aperturaData);
            $caja->estadoCaja()->save($apertura);
            // realizar la apertura de la caja ->create )
            // actualizar el saldo
            $caja->update(['saldo_caja'=>$request->input('saldo')]);
            return ["cod"=>"00","msg"=>"Caja abierta Correctamente","datos"=>["id"=>$caja->id,"descripcion"=>$caja->descripcion]];
        } catch( ModelNotFoundException $e){
            return ["cod"=>"04","msg"=>"no existen datos","error"=>$e->getMessage()];
        } catch (\Illuminate\Validation\ValidationException $e){
            return ["cod"=>"06","msg"=>"Error a insertar los datos","error"=>$e->getMessage()];
        } catch (\Exception $e) {
            return ["cod"=>"99","msg"=>"Error general","error"=>$e->getMessage()];
        }
    }

    public function cerrarCaja(UpdateCajaRequest $request,$id){
        try {
            $caja = Caja::findOrfail($id);
            $date = \date('Y-m-d h:i:s a', \time());
            $caja->estadoCaja;
            $estado = $caja->estadoCaja->max('id');
            if(!$caja->estadoCaja->last()->estado){
                return ["cod"=>"11","msg"=>"Caja ya cerrada"];
            }
            // validar pin
            // if($request->input('pin') == 'xx'){
            //     return ["cod"=>"12","msg"=>"Pin incorrecto para la caja"];
            // }
            // realizar la apertura de la caja ->create )
            // actualizar el saldo $caja->update(['saldo_caja'=>$request->input('saldo')])
            $caja->estadoCaja->last()->usuario_id='1';
            $caja->estadoCaja->last()->saldo_cierre=$caja->saldo_actual;
            $caja->estadoCaja->last()->fecha_cierre=$date;
            $caja->estadoCaja->last()->estado=0;
            $caja->estadoCaja->last()->save();
            $caja->update(['saldo_caja'=>0]);

            return ["cod"=>"00","msg"=>"Caja cerrada"];
        } catch( ModelNotFoundException $e){
            return ["cod"=>"04","msg"=>"no existen datos","error"=>$e->getMessage()];
        } catch (\Exception $e) {
            return ["cod"=>"99","msg"=>"Error general","error"=>$e->getMessage()];
        }
    }
}
