<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePerfilRequest;
use App\Http\Requests\UpdatePerfilRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Perfil;
use App\Models\Acceso;
use App\Models\OpcionMenu;

class PerfilController extends Controller{
    private $c_reg_panel = 25;
    private $c_reg_lista = 10;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($pag=0){
        $c_paginas = ceil(Perfil::count()/$this->c_reg_panel);
        $salto = $pag*$this->c_reg_panel;

        $query = Perfil::select("id","descripcion","observacion");

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
     * @param  \App\Http\Requests\StorePerfilRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePerfilRequest $request){
        try {
            $campos = $this->validate($request,[
                "descripcion"=>"required|string",
                "observacion"=>"string",
            ]);

            $accesos = $request->input('accesos');
            $opcionMenu = OpcionMenu::select("id")->get();
            $campos['descripcion'] = strtoupper($campos['descripcion']);
            $perfil = Perfil::create($campos);
            foreach ($opcionMenu->toArray() as $key => $value) {
                if(!in_array(["opcion_id"=>$value['id'],"acceso"=>false], $accesos)){
                    array_push($accesos, ["opcion_id"=>$value['id'],"acceso"=>false]);
                }
            }

            //return[$accesos];
            foreach ($accesos as $key => $value) {
                if(!isset($value['opcion_id']) || $value['opcion_id'] == ''){
                    continue;
                }
                if(!isset($value['acceso']) || $value['acceso'] == ''){
                    $value['acceso'] = false;
                }

                $camposAcceso = ['opcion_id'=>$value['opcion_id'], 'acceso'=>$value['acceso']];
                $accesoTemp = new Acceso($camposAcceso);
                try{
                    $perfil->accesos()->save($accesoTemp);
                }catch(\Exception $e){
                }

            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return ["cod"=>"06","msg"=>"Error al insertar los datos","errores"=>[$e->errors()]];

        } catch (\Exception $e) {
            return ["cod"=>"05","msg"=>"Error al insertar los datos","error"=>$e->getMessage()];
        }
        return ["cod"=>"00","msg"=>"todo correcto"];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Perfil  $perfil
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        try {
            $perfil = Perfil::findOrfail($id);
            $perfil->accesos;
            return ["cod"=>"00","msg"=>"todo correcto","datos"=>[$perfil]];
        } catch( ModelNotFoundException $e){
            return ["cod"=>"04","msg"=>"no existen datos","error"=>$e->getMessage()];
        } catch (\Exception $e) {
            return ["cod"=>"99","msg"=>"Error general","error"=>$e->getMessage()];
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Perfil  $perfil
     * @return \Illuminate\Http\Response
     */
    public function edit(Perfil $perfil){
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePerfilRequest  $request
     * @param  \App\Models\Perfil  $perfil
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePerfilRequest $request, $id){
        try {
            $perfil = Perfil::findOrfail($id);
            $campos = $this->validate($request,[
                "descripcion"=>"required|string",
                "observacion"=>"string",
            ]);

            $perfil->accesos()->delete();

            foreach ($request->input('accesos') as $key => $value) {
                if(!isset($value['opcion_id']) || $value['opcion_id'] == ''){
                    continue;
                }
                if(!isset($value['acceso']) || $value['acceso'] == ''){
                    $value['acceso'] = false;
                }
                $camposAcceso = ['opcion_id'=>$value['opcion_id'], 'acceso'=>$value['acceso']];
                $accesoTemp = new Acceso($camposAcceso);
                try{
                    $perfil->accesos()->save($accesoTemp);
                }catch(\Exception $e){
                }
            }

            $perfil->update($campos);
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
     * @param  \App\Models\Perfil  $perfil
     * @return \Illuminate\Http\Response
     */
    public function destroy(Perfil $perfil)
    {
        //
    }
}
