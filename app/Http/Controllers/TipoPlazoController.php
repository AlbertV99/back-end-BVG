<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTipoPlazoRequest;
use App\Http\Requests\UpdateTipoPlazoRequest;
use App\Models\TipoPlazo;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TipoPlazoController extends Controller{
    private $c_reg_panel = 25;
    private $c_reg_lista = 10;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($pag=0){
        $c_paginas = ceil(TipoPlazo::count()/$this->c_reg_panel);
        $salto = $pag*$this->c_reg_panel;

        $query = TipoPlazo::select("id","descripcion","factor_divisor","dias_vencimiento","interes");

        $query = $query->orderBy("dias_vencimiento");

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
     * @param  \App\Http\Requests\StoreTipoPlazoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTipoPlazoRequest $request){
        try {
            $campos = $this->validate($request,[
                "descripcion"=>"required|string",
                "factor_divisor"=>"required|integer",
                "dias_vencimiento"=>"required|integer",
                "interes"=>"required|numeric"
            ]);

            $tipoPlazo = TipoPlazo::create($campos);

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
     * @param  \App\Models\TipoPlazo  $tipoPlazo
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        try {
            $tipoPlazo = TipoPlazo::findOrfail($id);
            return ["cod"=>"00","msg"=>"todo correcto","datos"=>[$tipoPlazo]];
        } catch( ModelNotFoundException $e){
            return ["cod"=>"04","msg"=>"no existen datos","error"=>$e->getMessage()];
        } catch (\Exception $e) {
            return ["cod"=>"99","msg"=>"Error general","error"=>$e->getMessage()];
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TipoPlazo  $tipoPlazo
     * @return \Illuminate\Http\Response
     */
    public function edit(TipoPlazo $tipoPlazo){
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTipoPlazoRequest  $request
     * @param  \App\Models\TipoPlazo  $tipoPlazo
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTipoPlazoRequest $request, $id){
        try {
            $tipoPlazo = TipoPlazo::findOrfail($id);
            $campos = $this->validate($request,[
                "descripcion"=>"required|string",
                "factor_divisor"=>"required|integer",
                "dias_vencimiento"=>"required|integer",
                "interes"=>"required|numeric"
            ]);

            $tipoPlazo->update($campos);
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
     * @param  \App\Models\TipoPlazo  $tipoPlazo
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        try {
            $barrio = TipoPlazo::where("id",$id);
            $barrio->delete();

            return ["cod"=>"00","msg"=>"todo correcto"];
        } catch (\Exception $e) {
            return ["cod"=>"08","msg"=>"Error al eliminar el registro","errores"=>[$e->getMessage() ]];
        }
    }
}
