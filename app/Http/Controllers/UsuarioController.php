<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

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

        $query = Usuario::select("usuario.id","usuario.nombre_usuario","usuario.nombre","usuario.apellido","usuario.cedula","usuario.fecha_nacimiento","usuario.email","perfil.descripcion","usuario.restablecer_password")
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
                "nombre_usuario"=>"required|string|min:4",
                "nombre"=>"required|string",
                "apellido"=>"required|string",
                "cedula"=>"required|string",
                "password"=>"required|string|confirmed|min:6|",
                "fecha_nacimiento"=>"required|date",
                "email"=>"required|string",
                "perfil_id"=>"required|integer"
            ]);

            $campos['restablecer_password'] = false;
            $encriptado = bcrypt($campos['password']);
            $campos['password'] = $encriptado;
           // return[$campos];
            $usuario = Usuario::create($campos);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return ["cod"=>"06","msg"=>"Error al insertar los datos","errores"=>[$e->errors() ]];

        } catch (\Exception $e) {
            return ["cod"=>"05","msg"=>"Error al insertar los datos","error"=>$e->getMessage()];
        }
        return ["cod"=>"00","msg"=>"todo correcto"];
    }

    public function login(StoreUsuarioRequest $request){
        $credentials = $request->validate([
            'usuario' => ['required', 'string'],
            'password' => ['required'],
        ]);
        if(Auth::attempt(['nombre_usuario' => $credentials['usuario'], 'password' => $credentials['password']])){
            $usuario = Auth::user();
            $success['token'] =  $usuario->createToken($credentials['usuario'])->plainTextToken;
            $success['name'] =  $usuario->nombre_usuario;
            $success['perfil'] =  $usuario->perfil->descripcion;
            $success['menu'] = $this->obtenerDatosLogueo();
            return ["cod"=>"00","msg"=>"todo correcto","success"=>$success];
        }else{
            return ["cod"=>"11","msg"=>"Usuario o contraseña incorrecta"];
        }
    }

    public function logout(StoreUsuarioRequest $request){
        try{
            auth()->user()->currentAccessToken()->delete();
            Auth::guard('web')->logout();

            return ["cod"=>"00","msg"=>"todo correcto"];
        }catch (\Illuminate\Validation\ValidationException $e){
            return ["cod"=>"99","msg"=>"Error general","errores"=>[$e->errors() ]];
        }

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
    public function edit(Usuario $usuario){
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUsuarioRequest  $request
     * @param  \App\Models\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUsuarioRequest $request, $id){
        try {
            $usuario = Usuario::findOrfail($id);
            //return ["cod"=>$usuario];
            $campos = $this->validate($request,[
                "nombre_usuario"=>"required|string",
                "nombre"=>"required|string",
                "apellido"=>"required|string",
                "cedula"=>"required|string",
                "fecha_nacimiento"=>"required|date",
                "email"=>"required|string",
                "perfil_id"=>"required|integer"
            ]);
            $campos['restablecer_password'] = false;
            $usuario->update($campos);

            return ["cod"=>"00","msg"=>"todo correcto"];
        } catch (\Illuminate\Validation\ValidationException $e) {
            return ["cod"=>"06","msg"=>"Error al insertar los datos","errores"=>[$e->errors() ]];

        } catch(ModelNotFoundException $e){
            return ["cod"=>"04","msg"=>"no existen datos","error"=>$e->getMessage()];
        } catch (\Exception $e) {
            return ["cod"=>"99","msg"=>"Error al insertar los datos","error"=>$e->getMessage()];
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUsuarioRequest  $request
     * @param  \App\Models\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function cambiarContrasenha(UpdateUsuarioRequest $request, $id){
        try {
            $usuario = Usuario::findOrfail($id);
            $campos = $this->validate($request,[
                "password"=>"required|string|confirmed|min:6|"
            ]);
            $campos['restablecer_password'] = false;
            $encriptado = bcrypt($campos['password']);
            $campos['password'] = $encriptado;
            $usuario->update($campos);

            return ["cod"=>"00","msg"=>"todo correcto"];
        } catch (\Illuminate\Validation\ValidationException $e) {
            return ["cod"=>"06","msg"=>"Error al insertar los datos","errores"=>[$e->errors() ]];

        } catch(ModelNotFoundException $e){
            return ["cod"=>"04","msg"=>"no existen datos","error"=>$e->getMessage()];
        } catch (\Exception $e) {
            return ["cod"=>"99","msg"=>"Error al insertar los datos","error"=>$e->getMessage()];
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        try {
            if($id === "1"){
                return ["cod"=>"11","msg"=>"No se puede eliminar este usuario"];
            }

            $usuario = Usuario::findOrfail($id);
            //return ["cod"=>$usuario];
            $usuario->delete();

            return ["cod"=>"00","msg"=>"Eliminado correctamente"];
        } catch( ModelNotFoundException $e){
            return ["cod"=>"04","msg"=>"no existen datos","error"=>$e->getMessage()];
        } catch (\Exception $e) {
            return ["cod"=>"08","msg"=>"Error al eliminar el registro","errores"=>[$e->getMessage() ]];
        }
    }

    private function obtenerDatosLogueo(){
        $usuario = Auth::user();
        // $usuario = Usuario::findOrfail(1);

        $usuario->perfil;
        $accesos = $usuario->perfil->accesos->where('acceso', 'true');

        $agrupadores = [];
        $agrupadores_filtrado= [];
        $historial = [];
        foreach ($accesos as $acceso) {
            $agrupador = $acceso->opcionesMenu->agrupador;
            $b=-1;
            $agrupadores[] = $agrupador;
            foreach ($agrupadores_filtrado as $key => $filtrado) {
                if($agrupador["id"]== $filtrado['id']){
                    $b=$key;
                }
            }

            if($b==-1){
                $temp=$agrupador->toArray();
                $opcionTemp = $acceso->opcionesMenu;
                $opcionTemp['dir_imagen'] = 'http://'.request()->getHttpHost()."/".$opcionTemp['dir_imagen'];
                unset($opcionTemp['agrupador']);
                $temp["opciones"]=[$opcionTemp];
                $agrupadores_filtrado[]=$temp;
            }else{
                $opcionTemp = $acceso->opcionesMenu;
                $opcionTemp['dir_imagen'] = 'http://'.request()->getHttpHost()."/".$opcionTemp['dir_imagen'];
                unset($opcionTemp['agrupador']);
                $agrupadores_filtrado[$b]["opciones"][]=$opcionTemp;
            }
        }

        // foreach ($agrupadores as $agrupador) {
        // }
        return $agrupadores_filtrado;
    }
}
