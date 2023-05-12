<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreagrupadorRequest;
use App\Http\Requests\UpdateagrupadorRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Agrupador;

class AgrupadorController extends Controller{
    private $c_reg_panel = 25;
    private $c_reg_lista = 10;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($pag=0){
        $c_paginas = ceil(Agrupador::count()/$this->c_reg_panel);
        $salto = $pag*$this->c_reg_panel;

        $query = Agrupador::select("id","icono","descripcion");
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
     * @param  \App\Http\Requests\StoreagrupadorRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreagrupadorRequest $request){
        try {
            $campos = $this->validate($request,[
                "descripcion"=>"required|string",
                "observacion"=>"string",
                "icono"=>"string"
            ]);
            //AGREGAR PARA OPCIONES DE MENU

            $barrio = Agrupador::create($campos);

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
     * @param  \App\Models\agrupador  $agrupador
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        try {
            $barrio = Agrupador::findOrfail($id);
            return ["cod"=>"00","msg"=>"todo correcto","datos"=>[$barrio]];
        } catch( ModelNotFoundException $e){
            return ["cod"=>"04","msg"=>"no existen datos","error"=>$e->getMessage()];
        } catch (\Exception $e) {
            return ["cod"=>"99","msg"=>"Error general","error"=>$e->getMessage()];
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\agrupador  $agrupador
     * @return \Illuminate\Http\Response
     */
    public function edit(agrupador $agrupador)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateagrupadorRequest  $request
     * @param  \App\Models\agrupador  $agrupador
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateagrupadorRequest $request, $id){
        try {
            $barrio = Agrupador::findOrfail($id);
            $campos = $this->validate($request,[
                "descripcion"=>"required|string",
                "observacion"=>"string",
                "icono"=>"string"
            ]);
            //AGREGAR PARA OPCIONES DE MENU
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
     * @param  \App\Models\agrupador  $agrupador
     * @return \Illuminate\Http\Response
     */
    public function destroy(agrupador $agrupador)
    {
        //
    }
}
