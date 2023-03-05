<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Models\Cliente;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClienteController extends Controller{
    private $c_reg_panel = 25;
    private $c_reg_lista = 10;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($pag=0){
        $c_paginas = ceil(Cliente::count()/$this->c_reg_panel);
        $salto = $pag*$this->c_reg_panel;

        $query = Cliente::select("cliente.id","barrio.nombre","cliente.documento","cliente.nombre","cliente.apellido","cliente.tipo_documento","cliente.f_nacimiento","cliente.correo","cliente.direccion","cliente.sexo","estado_civil.descripcion")
        ->join("barrio", "barrio.id", "cliente.barrio")
        ->join("estado_civil", "estado_civil.id", "cliente.estado_civil")
        ;
        // if($busqueda !=""){
        //     $query = $query->where("usuario.nombre_usuario","like",$busqueda)->orWhere("usuario.nombre","like",$busqueda)->orWhere("usuario.apellido","like",$busqueda)->orWhere("usuario.apellido","like",$busqueda);
        // }
        $query = $query->skip($salto)->take($this->c_reg_panel)->orderBy("cliente.documento");

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
     * @param  \App\Http\Requests\StoreClienteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreClienteRequest $request){

        try {
            $campos = $this->validate($request,[
                'barrio'=>'required|string',
                'documento'=>'required|string',
                'tipo_documento'=>'required|integer',
                'nombre'=>'required|string',
                'apellido'=>'required|string',
                'f_nacimiento'=>'date',
                'correo'=>'required|string',
                'direccion'=>'string',
                'sexo'=>'required|string',
                'observaciones'=>'required|string',
                'estado_civil'=>'required|integer',
            ]);

            $usuario = Cliente::create($campos);

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
     * @param  int  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        try {
            $cliente = Cliente::findOrfail($id);

            return ["cod"=>"00","msg"=>"todo correcto","datos"=>[$cliente]];
        } catch( ModelNotFoundException $e){
            return ["cod"=>"04","msg"=>"no existen datos","error"=>$e->getMessage()];
        } catch (\Exception $e) {
            return ["cod"=>"99","msg"=>"Error general","error"=>$e->getMessage()];
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function edit(Cliente $cliente){
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateClienteRequest  $request
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateClienteRequest $request, $id){
        try {
            $cliente = Cliente::findOrfail($id);
            $campos = $this->validate($request,[
                'barrio'=>'required|string',
                'documento'=>'required|string',
                'tipo_documento'=>'required|integer',
                'nombre'=>'required|string',
                'apellido'=>'required|string',
                'f_nacimiento'=>'date',
                'correo'=>'required|string',
                'direccion'=>'string',
                'sexo'=>'required|string',
                'observaciones'=>'required|string',
                'estado_civil'=>'required|integer',
            ]);

             $cliente->update($campos);
             return ["cod"=>"00","msg"=>"todo correcto"];
        } catch(ModelNotFoundException $e){
            return ["cod"=>"04","msg"=>"no existen datos","error"=>$e->getMessage()];
        } catch (\Exception $e) {
            return ["cod"=>"99","msg"=>"Error general","error"=>$e->getMessage()];
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        try {
            $usuario = Cliente::where("id",$id);
            $usuario->delete();

            return ["cod"=>"00","msg"=>"todo correcto"];
        } catch (\Exception $e) {
            return ["cod"=>"08","msg"=>"Error al eliminar el registro","errores"=>[$e->getMessage() ]];
        }
    }
}
