<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOpcionMenuRequest;
use App\Http\Requests\UpdateOpcionMenuRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\OpcionMenu;

class OpcionMenuController extends Controller{
    private $c_reg_panel = 25;
    private $c_reg_lista = 10;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($pag=0){
        $c_paginas = ceil(OpcionMenu::count()/$this->c_reg_panel);
        $salto = $pag*$this->c_reg_panel;

        $query = OpcionMenu::select("opcion_menu.id","opcion_menu.observacion","opcion_menu.descripcion","opcion_menu.dir_imagen","agrupador.descripcion as dsc_agrupador", "agrupador.id as dsc_id")
        ->join('agrupador','agrupador.id','opcion_menu.agrupador_id');
        // if($busqueda !=""){
        //     $query = $query->where("usuario.nombre_usuario","like",$busqueda)->orWhere("usuario.nombre","like",$busqueda)->orWhere("usuario.apellido","like",$busqueda)->orWhere("usuario.apellido","like",$busqueda);
        // }
        $query = $query->orderBy("agrupador.descripcion");

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
     * @param  \App\Http\Requests\StoreOpcionMenuRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOpcionMenuRequest $request){
        try {
            $campos = $this->validate($request,[
                "descripcion"=>"required|string",
                "observacion"=>"string",
                "agrupador_id"=>"required|integer",
                "direccion"=>"required|string",
                "dir_imagen"=>"required|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
            ]);
            $imageName = time().'.'.$request->dir_imagen->extension();
            $request->dir_imagen->move(public_path('imagenes/opcionMenu'), $imageName);
            $campos['dir_imagen'] = 'imagenes/opcionMenu/'.$imageName;
            //AGREGAR PARA OPCIONES DE MENU

            $barrio = OpcionMenu::create($campos);

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
     * @param  \App\Models\OpcionMenu  $opcionMenu
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        try {
            $barrio = OpcionMenu::findOrfail($id);
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
     * @param  \App\Models\OpcionMenu  $opcionMenu
     * @return \Illuminate\Http\Response
     */
    public function edit(OpcionMenu $opcionMenu){
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOpcionMenuRequest  $request
     * @param  \App\Models\OpcionMenu  $opcionMenu
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOpcionMenuRequest $request, $id){
        try {
            $barrio = OpcionMenu::findOrfail($id);
            $campos = $this->validate($request,[
                "descripcion"=>"required|string",
                "observacion"=>"string",
                "agrupador_id"=>"required|integer",
                "dir_imagen"=>"required|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
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
     * @param  \App\Models\OpcionMenu  $opcionMenu
     * @return \Illuminate\Http\Response
     */
    public function destroy(OpcionMenu $opcionMenu)
    {
        //
    }
}
