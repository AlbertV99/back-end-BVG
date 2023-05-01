<?php

namespace App\Http\Controllers;

use App\Models\Operaciones;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOperacionesRequest;
use App\Http\Requests\UpdateOperacionesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\ConceptosCaja;
use App\Models\Solicitud;
use App\Models\Caja;
use App\Models\AperturaCaja;
use App\Models\HistorialEstado;
use App\Models\DetalleCuotaOperacion;

class OperacionesController extends Controller{
    private $c_reg_panel = 25;
    private $c_reg_lista = 10;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($pag=0){
        $c_paginas = ceil(Operaciones::count()/$this->c_reg_panel);
        $salto = $pag*$this->c_reg_panel;

        $query = Operaciones::select("operacion.id","operacion.caja","operacion.concepto","operacion.saldo_anterior","operacion.monto",
        "operacion.saldo_posterior","operacion.fecha_operacion","operacion.solicitud_id","operacion.usuario_id",
        "caja.descripcion as caja_descripcion","conceptos_caja.descripcion as concepto_caja","usuario.nombre_usuario as nombre_usuario")
        ->join("caja", "caja.id", "operacion.id")
        ->join("conceptos_caja","conceptos_caja.id","operacion.concepto")
        ->join("usuario","usuario.id","operacion.usuario_id");
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
    public function create(){

    }

    /**
     * Funcion para realizar un desembolso
     *
     * @param  \App\Http\Requests\StoreOperacionesRequest  $request
     * @return \Illuminate\Http\Response
     */

    public function desembolso(StoreOperacionesRequest $request){
        try {

            \date_default_timezone_set('America/Santiago');
            $date = \date('Y-m-d h:i:s a', \time());

            $campos = $this->validate($request,[
                "caja"=>"required|integer",
                "monto"=>"required|integer",
                "solicitud_id"=>"required|numeric",
                "usuario_id"=>"required|integer",
                "concepto"=>"interger",
            ]);

            $caja = Caja::findOrfail($campos["caja"]);
            $monto = $caja->saldo_actual;
            $aperturaCaja = $caja->estadoCaja->last()->estado;
            $usuario = $caja->estadoCaja->last()->usuario_id;
            $concepto_caja = ConceptosCaja::select('id')
           ->where('descripcion','=', 'Desembolso');
           //return["usuario"=>$usuario];
            $campos["concepto"] =$concepto_caja;
            $campos['concepto']= 2;
            if($monto < $campos["monto"]){
                return ["cod"=>"11","msg"=>"No tiene monto necesario en caja"];
            }
            if($aperturaCaja != 1){
                return ["cod"=>"11","msg"=>"Caja cerrada"];
            }
            if($usuario != $campos["usuario_id"]){
                return ["cod"=>"11","msg"=>"Usuario no ha abierto la caja "];
            }
            $solicitud = Solicitud::findOrfail($campos["solicitud_id"]);
            $estado = $solicitud->historialEstado->last()->estado_id;
            if($estado === null ||  $estado != 3){
                return ["cod"=>"11","msg"=>"El estado no es APROBADO"];
            }

            $saldo_anterior = $monto;
            $saldo_posterior = $monto - $campos["monto"];
            $campos["saldo_anterior"] = $saldo_anterior;
            $campos["saldo_posterior"] = $saldo_posterior;
            $campos["fecha_operacion"] =$date;
            $caja->update(["saldo_actual"=>$saldo_posterior]);
            $Operaciones = Operaciones::create($campos);
            $historial = new HistorialEstado(["estado_id"=>5,"observacion_cambio"=>"Desmbolso de la solicitud"]);
            $solicitud->historialEstado()->save($historial);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return ["cod"=>"06","msg"=>"Error al insertar los datos","errores"=>[$e->errors() ]];

        } catch (\Exception $e) {
            return ["cod"=>"05","msg"=>"Error al insertar los datos","error"=>$e->getMessage()];
        }
        //return["estado"=>$estado];
        return ["cod"=>"00","msg"=>"todo correcto"];
    }


    /**
     * Funcion para realizar un pago de cuota
     *
     * @param  \App\Http\Requests\StoreOperacionesRequest  $request
     * @return \Illuminate\Http\Response
     */

    public function pagarCuota(StoreOperacionesRequest $request){

        try {

            \date_default_timezone_set('America/Santiago');
            $date = \date('Y-m-d h:i:s a', \time());

            $campos = $this->validate($request,[
                "caja"=>"required|integer",
                "monto"=>"required|integer",
                "solicitud_id"=>"required|numeric",
                "usuario_id"=>"required|integer",
                "concepto"=>"interger",
            ]);

            $caja = Caja::findOrfail($campos["caja"]);
            $monto = $caja->saldo_actual;
            $aperturaCaja = $caja->estadoCaja->last()->estado;
            $usuario = $caja->estadoCaja->last()->usuario_id;

            $concepto_caja = ConceptosCaja::select('id')
           ->where('descripcion','=', 'Pago Cuota');
           //return["usuario"=>$usuario];
            $campos["concepto"] =$concepto_caja;
            $campos['concepto']= 2;

            if($aperturaCaja != 1){
                return ["cod"=>"11","msg"=>"Caja cerrada"];
            }

            if($usuario != $campos["usuario_id"]){
                return ["cod"=>"11","msg"=>"Usuario no ha abierto la caja "];
            }

            $solicitud = Solicitud::findOrfail($campos["solicitud_id"]);
            $solicitud->cuotas;
            $estado = $solicitud->historialEstado->last()->estado_id;
            if($estado === null ||  $estado != 5){
                return ["cod"=>"11","msg"=>"El estado no es DESEMBOLSADO"];
            }

            //VALIDAR ESTADOS DE CUOTAS
            $indicesCuotas = [];
            foreach ($request->input("cuotas") as $value) {
                $cuotaTemp = $solicitud->cuotas->search(function ($valor, int $pos) use ($value) {
                    return ($valor->id == $value['id']);
                });
                $indicesCuotas[]=$cuotaTemp;
                if($cuotaTemp && $solicitud->cuotas[$cuotaTemp]->estado != '1'){
                    throw  \Illuminate\Validation\ValidationException::withMessages(['Cuotas' => ['Una de las cuotas no existe para la solicitud seleccionada o ya fue pagada']]);
                }
            }


            $saldo_anterior = $monto;
            $saldo_posterior = $monto + $campos["monto"];
            $campos["saldo_anterior"] = $saldo_anterior;
            $campos["saldo_posterior"] = $saldo_posterior;
            $campos["fecha_operacion"] =$date;

            //actualizacion de saldo de caja
            // return ["cod"=>"11","msg"=>"Prueba","datos"=>$indicesCuotas,"cuotas"=>$solicitud->cuotas];
            $montoTemp = $campos['monto'];
            $detalle = [];
            foreach ($indicesCuotas as $posicion) {
                $saldoNew = ($montoTemp>$solicitud->cuotas[$posicion]->saldo ) ? 0 :$solicitud->cuotas[$posicion]->saldo - $montoTemp;
                $actualizacion = [];
                $actualizacion ['saldo'] = $saldoNew;
                if($saldoNew==0){
                    $actualizacion['estado'] = 2; // se cambia a pagado
                }
                $solicitud->cuotas[$posicion]->update($actualizacion);
                $detalle[] = new DetalleCuotaOperacion(['cuota_id'=>$montoTemp>$solicitud->cuotas[$posicion]->id]);


            }
            $caja->update(["saldo_actual"=>$saldo_posterior]);
            $Operaciones = Operaciones::create($campos);
            // AGREGAR LAS CUOTAS PAGADAS
            $Operaciones->detalles()->saveMany($detalle);
            // $historial = new HistorialEstado(["estado_id"=>5,"observacion_cambio"=>"Desmbolso de la solicitud"]);
            // $solicitud->historialEstado()->save($historial);

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
