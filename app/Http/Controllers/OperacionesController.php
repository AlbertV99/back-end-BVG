<?php

namespace App\Http\Controllers;

use App\Models\Operaciones;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOperacionesRequest;
use App\Http\Requests\UpdateOperacionesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\ConceptosCaja;
use App\Models\Solicitud;

class OperacionesController extends Controller
{
    private $c_reg_panel = 25;
    private $c_reg_lista = 10;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($pag=0)
    {
        $c_paginas = ceil(Operaciones::count()/$this->c_reg_panel);
        $salto = $pag*$this->c_reg_panel;

        $query = Operaciones::select("id","caja","concepto","saldo_anterior","monto",
        "saldo_posterior","fecha_operacion","solicitud_id","cuota_id","usuario_id");
        // if($busqueda !=""){
        //     $query = $query->where("usuario.nombre_usuario","like",$busqueda)->orWhere("usuario.nombre","like",$busqueda)->orWhere("usuario.apellido","like",$busqueda)->orWhere("usuario.apellido","like",$busqueda);
        // }
        $query = $query->orderByDesc("fecha_operacion");

        return ["cod"=>"00","msg"=>"todo correcto","pagina_actual"=>$pag,"cantidad_paginas"=>$c_paginas,"datos"=>$query->get()];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreOperacionesRequest  $request
     * @return \Illuminate\Http\Response
     */

    /*
     VALIDACIONES
        1- Verificar que la solicitud si o si en estado aprobado cualquier otro estado nel pastel [listo]
        2- Verificar que la caja tenga monto necesario para dar, este abierta
        3- Ver que el usuario que quiere hacer la transaccion sea el que abrio la caja previamente 
     PROCESOS
        1- Marcar la solicitud como desembolsado 
        2- Restar del saldo de la caja 
        3- Calcular el saldo anterior y el saldo posterior (sacar de la peticion post)
        4- Obtener la fecha de operacion desde el servidor
    */
    public function store(StoreOperacionesRequest $request)
    {
        try {
            $campos = $this->validate($request,[
                "caja"=>"required|integer",
                "concepto"=>"integer",
                "saldo_anterior"=>"required|integer",
                "monto"=>"required|integer",
                "saldo_posterior"=>"required|integer",
                "fecha_operacion"=>"required|date",
                "solicitud_id"=>"required|numeric",
                "cuota_id"=>"string",
                "usuario_id"=>"required|integer",
            ]);

            $campos['concepto']= 2;
            $solicitud = Solicitud::findOrfail($campos["solicitud_id"]);
            // $solicitud->estadosolicitud;
            $estado = $solicitud->historialEstado->last()->estado_id;
            if($estado === null ||  $estado != 3){
                return ["cod"=>"11","msg"=>"El estado no es APROBADO"];
            }


            $Operaciones = Operaciones::create($campos);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return ["cod"=>"06","msg"=>"Error al insertar los datos","errores"=>[$e->errors() ]];

        } catch (\Exception $e) {
            return ["cod"=>"05","msg"=>"Error al insertar los datos","error"=>$e->getMessage()];
        }
        //return["estado"=>$estado];
        return ["cod"=>"00","msg"=>"todo correcto"];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Operaciones  $operaciones
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $Operaciones = Operaciones::findOrfail($id);
            return ["cod"=>"00","msg"=>"todo correcto","datos"=>[$Operaciones]];
        } catch( ModelNotFoundException $e){
            return ["cod"=>"04","msg"=>"no existen datos","error"=>$e->getMessage()];
        } catch (\Exception $e) {
            return ["cod"=>"99","msg"=>"Error general","error"=>$e->getMessage()];
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Operaciones  $operaciones
     * @return \Illuminate\Http\Response
     */
    public function edit(Operaciones $operaciones)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOperacionesRequest  $request
     * @param  \App\Models\Operaciones  $operaciones
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOperacionesRequest $request, Operaciones $operaciones)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Operaciones  $operaciones
     * @return \Illuminate\Http\Response
     */
    public function destroy(Operaciones $operaciones)
    {
        //
    }
}
