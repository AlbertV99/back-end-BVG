<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Models\Cliente;
use App\Models\PerfilCliente;
use App\Models\TelefonoCliente;
use App\Models\Documento;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Carbon as BaseCarbon;

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
        ->join("estado_civil", "estado_civil.id", "cliente.estado_civil");
        // if($busqueda !=""){
        //     $query = $query->where("usuario.nombre_usuario","like",$busqueda)->orWhere("usuario.nombre","like",$busqueda)->orWhere("usuario.apellido","like",$busqueda)->orWhere("usuario.apellido","like",$busqueda);
        // }
        $query = $query->orderBy("cliente.documento");

        return ["cod"=>"00","msg"=>"todo correcto","pagina_actual"=>$pag,"cantidad_paginas"=>$c_paginas,"datos"=>$query->get()];
    }

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

            $telefono = json_decode($request->input("tel_cliente"));
            // $telefono = $request->input("tel_cliente");
            if( count($telefono)<1 ){throw  \Illuminate\Validation\ValidationException::withMessages(['Telefono' => ['Debe completar al menos un telefono']]);}

            $campos = $this->validate($request,[
                'barrio'=>'required|string',
                'documento'=>'required|string',
                'tipo_documento'=>'required|string',
                'nombre'=>'required|string',
                'apellido'=>'required|string',
                'f_nacimiento'=>'date',
                'correo'=>'required|string',
                'direccion'=>'string',
                'sexo'=>'required|string',
                'observaciones'=>'string',
                'estado_civil'=>'required|integer',
                "dir_imagen"=>"file|mimes:pdf",
                "venc_cedula"=>"date"

            ]);
            unset($campos['dir_imagen']);
            unset($campos['venc_cedula']);
            $usuario = Cliente::create($campos);
            if(isset($request->dir_imagen)){
                $datosArchivo =$this->guardarArchivo($request->dir_imagen,$campos['documento']);
                $archivo = new Documento(["nombre"=>$datosArchivo,"fecha_vencimiento"=>$request->venc_cedula]);
                $usuario->cedula()->save($archivo);
            }

            foreach($telefono as $key => $value){
                if(!isset($value->telefono_cliente) || $value->telefono_cliente == ''){
                    continue;
                }
                $camposTelefono = ['telefono'=> $value->telefono_cliente];
                $telefonoN = new TelefonoCliente($camposTelefono);
                $usuario->telefono()->save($telefonoN);

            }
            return ["cod"=>"00","msg"=>"todo correcto"];

        } catch (\Illuminate\Validation\ValidationException $e) {
            return ["cod"=>"06","msg"=>"Error al insertar los datos","errores"=>[$e->errors() ]];

        } catch (\Exception $e) {
            return ["cod"=>"05","msg"=>"Error al insertar los datos","error"=>$e->getMessage()];
        }

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
            $telefono = $cliente->telefono;
            $documentos = $cliente->cedula->last();



            return ["cod"=>"00","msg"=>"todo correcto","datos"=>[
                "id" => $cliente["id"],
                "barrio" => $cliente["barrio"],
                "documento" => $cliente["documento"],
                "tipo_documento" => $cliente["tipo_documento"],
                "nombre" => $cliente["nombre"],
                "apellido" => $cliente["apellido"],
                "f_nacimiento" => $cliente["f_nacimiento"],
                "correo" => $cliente["correo"],
                "direccion" => $cliente["direccion"],
                "sexo" => $cliente["sexo"],
                "observaciones" => $cliente["observaciones"],
                "estado_civil" => $cliente["estado_civil"],
                "telefono" => $telefono,
                "documentos" => $documentos]
            ];
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

            $telefono = json_decode($request->input("tel_cliente"));
            // $telefono = $request->input("tel_cliente");
            if( count($telefono)<1 ){throw  \Illuminate\Validation\ValidationException::withMessages(['Telefono' => ['Debe completar al menos un telefono']]);}
            $campos = $this->validate($request,[
                'barrio'=>'required|string',
                //'documento'=>'required|string',
                'tipo_documento'=>'required|integer',
                'nombre'=>'required|string',
                'apellido'=>'required|string',
                'f_nacimiento'=>'date',
                'correo'=>'required|string',
                'direccion'=>'string',
                'sexo'=>'required|string',
                'observaciones'=>'string',
                'estado_civil'=>'required|integer',
            ]);

                $cliente->telefono()->delete();

                foreach($telefono as $key => $value){
                    if(!isset($value->telefono_cliente) || $value->telefono_cliente == ''){
                        continue;
                    }
                    $camposTelefono = ['telefono'=> $value->telefono_cliente];
                    $telefono = new TelefonoCliente($camposTelefono);
                    $cliente->telefono()->save($telefono);
                }

            $cliente->update($campos);
            return ["cod"=>"00","msg"=>"todo correcto"];
        } catch(ModelNotFoundException $e){
            return ["cod"=>"04","msg"=>"no existen datos","error"=>$e->getMessage()];
        } catch (\Illuminate\Validation\ValidationException $e) {
            return ["cod"=>"06","msg"=>"Error al insertar los datos","errores"=>[$e->errors() ]];
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
            $cliente = Cliente::findOrfail($id);
            //$telefBD= $cliente->telefono;
           //return["telefBD"=>$telefBD];
            $cliente->delete();
            //$telefBD->delete();

            return ["cod"=>"00","msg"=>"Eliminado correctamente"];
        } catch(ModelNotFoundException $e){
            return ["cod"=>"04","msg"=>"no existen datos","error"=>$e->getMessage()];
        } catch (\Exception $e) {
            return ["cod"=>"08","msg"=>"Error al eliminar el registro","errores"=>[$e->getMessage() ]];
        }
    }

    public static function obtenerPerfil($id){
        try {
            //OBTECION DE CLIENTE A EVALUAR
            $cliente = Cliente::findOrfail($id);

            $tempFecha = BaseCarbon::parse($cliente->f_nacimiento);
            $edad = BaseCarbon::now()->diffInYears($tempFecha);
            $max = 0;

            //OBTENCION DE TODOS LOS PARAMETROS Y LOS RANGOS PARA ASIGNACION DE PUNTOS
            $parametrosCrudo = PerfilCliente::all();
            foreach ($parametrosCrudo as $value) {
                $value->parametros ;
                $max += $value->parametros->max('punto');
            }



            // EVALUACION DE EDAD
            $parametroEdad = $parametrosCrudo->find(1);
            $resEdad =$parametroEdad->parametros->filter(function ($value,$key) use ($edad) {
                return ($value->rango_inf <= intval($edad) && $value->rango_sup >= intval($edad) );
            });

            $pos = key(reset($resEdad));



            // EVALUACION DE PROMEDIO DE ATRASOS DE CUOTAS
            // $parametroEdad = $parametrosCrudo->find(1);
            // $resEdad = $parametroEdad->parametros->filter(function ($value,$key){
            //     return ($value->rango_inf <20 && $value->rango_sup > 20 );
            // });

            // EVALUACION DE MAXIMO ATRASO DE PAGOS
            // $parametroEdad = $parametrosCrudo->find(1);
            // $resEdad = $parametroEdad->parametros->filter(function ($value,$key){
            //     return ($value->rango_inf <20 && $value->rango_sup > 20 );
            // });


            // ESTRUCTURA PARA RETORNO DE RESULTADOS
            $clientePerfil = [
                "cliente"=>$cliente,
                "edad"=>$edad,
                "maximo_alcanzable"=>$max,
                "perfil"=>[
                    "total_puntos"=>$resEdad[$pos]->punto + 6,//SE SUMA CON LOS DEMAS PARAMETROS [6 por defecto temporal]
                    "edad"=>$resEdad[$pos]->punto,
                    "promedio_atraso"=>3,
                    "maximo_atraso"=>3
                ],
                "parametros"=>$parametrosCrudo
            ] ;
            return ["cod"=>"00","msg"=>"todo correcto","datos"=>$clientePerfil];

        } catch( ModelNotFoundException $e){
            return ["cod"=>"04","msg"=>"no existen datos","error"=>$e->getMessage()];
        } catch (\Exception $e) {
            return ["cod"=>"99","msg"=>"Error general","error"=>$e->getMessage()];
        }

    }

    private function guardarArchivo($archivo,$ci){
        $imageName = $ci."-".time().'.'.$archivo->extension();
        $archivo->move(public_path('imagenes/documentos'), $imageName);
        return 'imagenes/documentos/'.$imageName;

    }

}
