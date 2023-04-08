<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorebarrioRequest;
use App\Http\Requests\UpdatebarrioRequest;
use App\Models\barrio;

class BarrioController extends Controller{
    private $c_reg_panel = 25;
    private $c_reg_lista = 10;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($pag=0){
        $c_paginas = ceil(barrio::count()/$this->c_reg_panel);
        $salto = $pag*$this->c_reg_panel;

        $query = barrio::select("id","nombre","observacion");
        // if($busqueda !=""){
        //     $query = $query->where("usuario.nombre_usuario","like",$busqueda)->orWhere("usuario.nombre","like",$busqueda)->orWhere("usuario.apellido","like",$busqueda)->orWhere("usuario.apellido","like",$busqueda);
        // }
        $query = $query->orderBy("nombre");

        return ["cod"=>"00","msg"=>"todo correcto","pagina_actual"=>$pag,"cantidad_paginas"=>$c_paginas,"datos"=>$query->get()];

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorebarrioRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorebarrioRequest $request){
        try {
            $campos = $this->validate($request,[
                "nombre"=>"required|string",
                "observacion"=>"string"
            ]);

            $barrio = barrio::create($campos);

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
     * @param  \App\Models\barrio  $barrio
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        try {
            $barrio = barrio::findOrfail($id);
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
     * @param  \App\Models\barrio  $barrio
     * @return \Illuminate\Http\Response
     */
    public function edit(barrio $barrio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatebarrioRequest  $request
     * @param  \App\Models\barrio  $barrio
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatebarrioRequest $request, $id){
        try {
            $barrio = barrio::findOrfail($id);
            $campos = $this->validate($request,[
                "nombre"=>"required|string",
                "observacion"=>"string"
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
     * @param  \App\Models\barrio  $barrio
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        try {
            $barrio = barrio::where("id",$id);
            $barrio->delete();

            return ["cod"=>"00","msg"=>"todo correcto"];
        } catch (\Exception $e) {
            return ["cod"=>"08","msg"=>"Error al eliminar el registro","errores"=>[$e->getMessage() ]];
        }
    }
}
