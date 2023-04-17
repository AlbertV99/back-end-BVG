<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSolicitudRequest;
use App\Http\Requests\UpdateSolicitudRequest;
use App\Models\Solicitud;
use App\Models\ReferenciaPersonal;
use App\Models\ReferenciaComercial;
use App\Models\EstadoSolicitud;
use App\Models\HistorialEstado;

class SolicitudController extends Controller{
    private $c_reg_panel = 25;
    private $c_reg_lista = 10;
    private $estadoBD = ["pendiente","analizando","aprobado","rechazado","desembolsado","cancelado","finalizado"];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($estado,$pag=0){
        $idEstado = array_search(strtolower($estado),$this->estadoBD);
        $c_paginas = ceil(Solicitud::count()/$this->c_reg_panel);
        $salto = $pag*$this->c_reg_panel;

        $query=Solicitud::select("solicitud.id","cliente.documento","cliente.nombre","cliente.apellido","cliente.tipo_documento","solicitud.ingresos_actuales","solicitud.monto_credito","solicitud.interes","solicitud.tipo_plazo",)
        ->join("cliente", "cliente.id", "solicitud.cliente_id","estado_solicitud.descripcion")
        ->join("tipo_plazo", "tipo_plazo.id", "solicitud.tipo_plazo")
        // ->leftJoin('historial_estado', function($query) {
        //     $query->on('solicitud.id','=','historial_estado.solicitud_id')
        //     ->whereRaw('historial_estado.id IN (select MAX(historial_estado.id) from historial_estado as he join solicitud as s on he.solicitud_id = s.id group by s.id)');
        // })
        // ->leftJoin("estado_solicitud","estado_solicitud.id","historial_estado.estado_id")
        ;

        $query = $query->orderBy("cliente.documento");

        return ["cod"=>"00","msg"=>"todo correcto","pagina_actual"=>$pag,"cantidad_paginas"=>$c_paginas,"datos"=>$query->get()];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        //sin uso
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSolicitudRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSolicitudRequest $request){
        try {

            // return ["cod"=>"00","msg"=>"todo correcto","datos"=>$pendiente[0]->id,];

            if( count($request->input('ref_personales'))<1 ){throw  \Illuminate\Validation\ValidationException::withMessages(['Referencia Personal' => ['Debe completar al menos una referencia personal']]);}
            $campos = $this->validate($request,[
                'cliente_id'=>'required|string',
                'ingresos_actuales'=>'required|string',
                'monto_credito'=>'required|integer',
                'gastos_administrativos'=>'required|integer',
                'interes'=>'required|numeric',
                'interes_moratorio'=>'required|numeric',
                'tipo_plazo'=>'required|integer',
                'observacion'=>'string',
                'usuario_id'=>'integer',
                'vencimiento_retiro'=>'date',
            ]);

            $solicitud = Solicitud::create($campos);
            foreach ($request->input('ref_personales') as $key => $value) {
                $camposRef = ['cliente_id'=>$value['cliente_id'],     'relacion_cliente'=>$value['relacion_cliente']];
                $refPersTemp = new ReferenciaPersonal($camposRef);
                $solicitud->referenciaPersonal()->save($refPersTemp);
            }

            if(count($request->input('ref_comerciales'))>0 ){
                foreach ($request->input('ref_comerciales') as $key => $value) {
                    $camposRef = ['entidad'=>$value['entidad'],'monto_cuota'=>$value['monto_cuota'],
                    'estado'=>$value['estado'],'cuotas_pendientes'=>$value['cuotas_pendientes'],
                    'cuotas_totales'=>$value['cuotas_totales']];
                    $refPersTemp = new ReferenciaComercial($camposRef);
                    $solicitud->referenciaComercial()->save($refPersTemp);
                }
            }
            $pendiente = EstadoSolicitud::where("descripcion","Pendiente")->get();
            $historial = new HistorialEstado(["estado_id"=>$pendiente[0]->id,"observacion_cambio"=>"Creacion de Solicitud"]);

            $solicitud->historialEstado()->save($historial);



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
     * @param  \App\Models\Solicitud  $solicitud
     * @return \Illuminate\Http\Response
     */
    public function show( $id){
        try {
            $solicitud = Solicitud::findOrfail($id);
            $solicitud->cliente;
            $solicitud->tipoPlazo;
            $solicitud->historialEstado;
            $solicitud->referenciaPersonal;
            $solicitud->referenciaComercial;
            foreach ($solicitud->historialEstado as $historial) {
                $historial->estadoSolicitud;
            }
            foreach ($solicitud->referenciaPersonal as $refPersonal) {
                $refPersonal->cliente;
            }
            $analisis = $this->calculosAnalisis($id);

            // $solicitud->put('analisis', $analisis);

            return ["cod"=>"00","msg"=>"todo correcto","analisis"=>$analisis,"datos"=>$solicitud];
        } catch( ModelNotFoundException $e){
            return ["cod"=>"04","msg"=>"no existen datos","error"=>$e->getMessage()];
        } catch (\Exception $e) {
            return ["cod"=>"99","msg"=>"Error general","error"=>$e->getMessage()];
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Solicitud  $solicitud
     * @return \Illuminate\Http\Response
     */
    public function edit(Solicitud $solicitud){
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSolicitudRequest  $request
     * @param  \App\Models\Solicitud  $solicitud
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSolicitudRequest $request, Solicitud $solicitud){
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSolicitudRequest  $request
     * @param  \App\Models\Solicitud  $solicitud
     * @return \Illuminate\Http\Response
     */
    public function actualizarReferencias(UpdateSolicitudRequest $request,$id){
        try {
            $solicitud = Solicitud::findOrfail($id);
            $refsPersBD = $solicitud->referenciaPersonal;
            $refsComBD = $solicitud->referenciaComercial;
            if( count($request->input('ref_personales'))<1 ){
                throw  \Illuminate\Validation\ValidationException::withMessages(['Referencia Personal' => ['Debe completar al menos una referencia personal']]);
            }
            if( count($request->input('ref_comerciales'))<1 ){
                throw  \Illuminate\Validation\ValidationException::withMessages(['Referencia Comercial' => ['Debe completar al menos una referencia comercial']]);
            }

            $refPersonalPeticion = $request->input('ref_personales');
            $refComercialPeticion = $request->input('ref_comerciales');

            foreach ($refPersonalPeticion as $referencia) {
                $b= 1;
                foreach ($refsPersBD as $refDB) {
                    if($referencia['cliente_id'] == $refDB['cliente_id']){
                        $b=0;
                    }
                }
                if($b){
                    $refPersTemp = new ReferenciaPersonal($referencia);
                    $solicitud->referenciaPersonal()->save($refPersTemp);
                }

            }

            foreach ($refComercialPeticion as $referencia) {
                $b= 1;
                foreach ($refsComBD as $refDB) {
                    if(($referencia['entidad'] == $refDB['entidad'])
                    && ($referencia['estado'] == $refDB['estado'])
                    && ($referencia['monto_cuota'] == $refDB['monto_cuota'])
                    && ($referencia['cuotas_pendientes'] == $refDB['cuotas_pendientes'])
                    && ($referencia['cuotas_totales'] == $refDB['cuotas_totales'])){
                        $b=0;
                    }
                }
                if($b){
                    $refPersTemp = new ReferenciaComercial($referencia);
                    $solicitud->referenciaComercial()->save($refPersTemp);
                }

            }

            return ["cod"=>"00","msg"=>"Actualización Correcta"];
        } catch( ModelNotFoundException $e){
            return ["cod"=>"04","msg"=>"no existen datos","error"=>$e->getMessage()];
        } catch (\Exception $e) {
            return ["cod"=>"99","msg"=>"no existen datos","error"=>$e->getMessage()];
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Solicitud  $solicitud
     * @return \Illuminate\Http\Response
     */
    public function destroy(Solicitud $solicitud){
        //
    }

    public function historial($id){
        try {
            $solicitud = Solicitud::findOrfail($id);
            return ["cod"=>"00","msg"=>"todo correcto","datos"=>$solicitud];
        } catch( ModelNotFoundException $e){
            return ["cod"=>"04","msg"=>"no existen datos","error"=>$e->getMessage()];
        } catch (\Exception $e) {
            return ["cod"=>"99","msg"=>"Error general","error"=>$e->getMessage()];
        }
    }

    public function calculosAnalisis($idSolicitud){
        $solicitud = Solicitud::findOrfail($idSolicitud);
        $solicitud->referenciaComercial;
        $total_cuotas= $solicitud->referenciaComercial->sum(function ($ref) {
                    return (($ref['estado']=="ACTIVO")?$ref["monto_cuota"]:0);
                });
        $calculoCuotas = 0;
        $calculos = [];
        for ($mes=0; $mes < 3 ; $mes++) {
            $calculoCuotas = $solicitud->referenciaComercial->sum(function ($ref) use ($mes){
                return (($ref['cuotas_pendientes']>=($mes+1))?$ref['monto_cuota']:0);
            });
            $calculos[$mes]=[
                "mes"=>$mes+1,
                "ingresos"=>$solicitud->ingresos_actuales,
                "costos"=>$calculoCuotas,
                "restante"=>$solicitud->ingresos_actuales-$calculoCuotas,
                "cuotaN"=>50000,
                "total"=>$total_cuotas,
            ];

        }
        return $calculos;

    }

}
