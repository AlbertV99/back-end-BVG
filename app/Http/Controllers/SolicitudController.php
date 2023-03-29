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

        $query = $query->skip($salto)->take($this->c_reg_panel)->orderBy("cliente.documento");

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

            if( count($request->input('ref_personales'))<1 ){throw  \Illuminate\Validation\ValidationException::withMessages([
   'Referencia Personal' => ['Debe completar al menos una referencia personal']]);}
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
                $camposRef = ['cliente_id'=>$value['id_cliente'],     'relacion_cliente'=>$value['relacion']];
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
    public function show(Solicitud $solicitud){
        //
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Solicitud  $solicitud
     * @return \Illuminate\Http\Response
     */
    public function destroy(Solicitud $solicitud){
        //
    }
}