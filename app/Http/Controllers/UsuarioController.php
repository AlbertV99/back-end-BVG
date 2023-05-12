<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Usuario;

class UsuarioController extends Controller{
    private $c_reg_panel = 25;
    private $c_reg_lista = 10;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($pag=0){
        $c_paginas = ceil(Usuario::count()/$this->c_reg_panel);
        $salto = $pag*$this->c_reg_panel;

        $query = Usuario::select("usuario.id","usuario.nombre_usuario","usuario.nombre","usuario.apellido","usuario.cedula","usuario.fecha_nacimiento","usuario.email","perfil.descripcion","usuario.restablecer_pass")
        ->join("perfil","perfil.id","usuario.perfil_id");
        // if($busqueda !=""){
        //     $query = $query->where("usuario.nombre_usuario","like",$busqueda)->orWhere("usuario.nombre","like",$busqueda)->orWhere("usuario.apellido","like",$busqueda)->orWhere("usuario.apellido","like",$busqueda);
        // }
        $query = $query->orderBy("usuario.nombre_usuario");

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
     * @param  \App\Http\Requests\StoreUsuarioRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUsuarioRequest $request){

        try {
            $campos = $this->validate($request,[
                "nombre_usuario"=>"required|string",
                "nombre"=>"required|string",
                "apellido"=>"required|string",
                "cedula"=>"required|string",
                "pass"=>"required|string",
                "fecha_nacimiento"=>"required|date",
                "email"=>"required|string",
                "perfil_id"=>"required|integer",
                "restablecer_pass"=>"required|boolean"
            ]);

            $usuario = Usuario::create($campos);

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
     * @param  \App\Models\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        try {
            $usuario = Usuario::findOrfail($id);
            $usuario->perfil;
            return ["cod"=>"00","msg"=>"todo correcto","datos"=>[$usuario]];
        } catch( ModelNotFoundException $e){
            return ["cod"=>"04","msg"=>"no existen datos","error"=>$e->getMessage()];
        } catch (\Exception $e) {
            return ["cod"=>"99","msg"=>"Error general","error"=>$e->getMessage()];
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function edit(Usuario $usuario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUsuarioRequest  $request
     * @param  \App\Models\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUsuarioRequest $request, $id)
    {
        try {
            $usuario = Usuario::findOrfail($id);
            //return ["cod"=>$usuario];
            $campos = $this->validate($request,[
                "nombre_usuario"=>"required|string",
                "nombre"=>"required|string",
                "apellido"=>"required|string",
                "cedula"=>"required|string",
                "pass"=>"required|string",
                "fecha_nacimiento"=>"required|date",
                "email"=>"required|string",
                "perfil_id"=>"required|integer",
                "restablecer_pass"=>"required|boolean"
            ]);

            $usuario->update($campos);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return ["cod"=>"06","msg"=>"Error al insertar los datos","errores"=>[$e->errors() ]];

        } catch (\Exception $e) {
            return ["cod"=>"05","msg"=>"Error al insertar los datos","error"=>$e->getMessage()];
        } catch(ModelNotFoundException $e){
            return ["cod"=>"04","msg"=>"no existen datos","error"=>$e->getMessage()];
        }
        return ["cod"=>"00","msg"=>"todo correcto"];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Usuario $usuario)
    {
        //
    }

    public function obtenerDatosLogueo(){
        $usuario = Usuario::findOrfail(1);
        $usuario->perfil;
        $accesos = $usuario->perfil->accesos;
        $agrupadores = [];
        foreach ($accesos as $opciones) {
            $accesos->opcionMenu;
            // $agrupadores[]=>
        }
        return ["cod"=>"00","msg"=>"todo correcto","usuario"=>$usuario];
    }
}
