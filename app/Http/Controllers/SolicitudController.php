<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSolicitudRequest;
use App\Http\Requests\UpdateSolicitudRequest;
use App\Models\Solicitud;
use App\Models\ReferenciaPersonal;
use App\Models\ReferenciaComercial;
use App\Models\EstadoSolicitud;
use App\Models\HistorialEstado;
use Illuminate\Support\Str;
use App\Models\TipoPlazo;
use App\Models\Cuotas;
use App\Models\EstadoCuota;
use Illuminate\Support\Carbon as BaseCarbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;


class SolicitudController extends Controller{
    private $c_reg_panel = 25;
    private $c_reg_lista = 10;
    private $estadoBD = ["pendiente","analizado","aprobado","rechazado","desembolsado","cancelado","finalizado"];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($estado,$pag=0){
        $idEstado = array_search(strtolower($estado),$this->estadoBD);
        $c_paginas = ceil(Solicitud::count()/$this->c_reg_panel);
        $salto = $pag*$this->c_reg_panel;
        $string = Str::upper($estado);

        if($string != 'TODO'){
            $pendiente = EstadoSolicitud::where("descripcion",$string)->get();
            $id = ['estado'=>$pendiente[0]->id];
        }


        $query=Solicitud::select("solicitud.id","cliente.documento","cliente.nombre","cliente.apellido","cliente.tipo_documento","solicitud.ingresos_actuales","solicitud.monto_credito","solicitud.interes","solicitud.tipo_plazo","solicitud.cant_cuotas","tipo_plazo.descripcion as descripcion_plazo")
        ->join("cliente", "cliente.id", "solicitud.cliente_id","estado_solicitud.descripcion")
        ->join("tipo_plazo", "tipo_plazo.id", "solicitud.tipo_plazo");
        if($string != 'TODO'){
            $query->where("solicitud.estado","=",$id);
        }
        // ->leftJoin('historial_estado', function($query) {
        //     $query->on('solicitud.id','=','historial_estado.solicitud_id')
        //     ->whereRaw('historial_estado.id IN (select MAX(historial_estado.id) from historial_estado as he join solicitud as s on he.solicitud_id = s.id group by s.id)');
        // })
        // ->leftJoin("estado_solicitud","estado_solicitud.id","historial_estado.estado_id")
        ;

        $query = $query->orderByDesc("solicitud.id");

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
                'monto_credito'=>'required|integer|min:0',
                'gastos_administrativos'=>'required|integer|min:0',
                'interes'=>'required|numeric|min:0',
                'interes_moratorio'=>'required|numeric|min:0',
                'tipo_plazo'=>'required|integer',
                'observacion'=>'string',
                'vencimiento_retiro'=>'date',
                'cant_cuotas'=>'required|integer',
                'inicio_cuota'=>'required|integer',
            ]);

            $usuarioLogueado = auth('sanctum')->user()->id;
            $campos['usuario_id'] = $usuarioLogueado;
            $refPersTemp=[];
            $refComTemp=[];

            if(count($request->input('ref_personales'))>0 ){
                foreach ($request->input('ref_personales') as $key => $value) {
                    $camposRef = ['nombres_apellido'=>$value['nombres_apellido'],'relacion_cliente'=>$value['relacion_cliente'],
                    'telefono'=>$value['telefono']];
                    $refPersTemp[] = new ReferenciaPersonal($camposRef);

                }
            }

            if(count($request->input('ref_comerciales'))>0 ){
                foreach ($request->input('ref_comerciales') as $key => $value) {
                    $camposRef = ['entidad'=>$value['entidad'],'monto_cuota'=>$value['monto_cuota'],
                    'estado'=>$value['estado'],'cuotas_pendientes'=>$value['cuotas_pendientes'],
                    'cuotas_totales'=>$value['cuotas_totales']];
                    $refComTemp[] = new ReferenciaComercial($camposRef);

                }
            }

            $solicitud = Solicitud::create($campos);
            $solicitud->referenciaPersonal()->saveMany($refPersTemp);
            $solicitud->referenciaComercial()->saveMany($refComTemp);

            $pendiente = EstadoSolicitud::where("descripcion","PENDIENTE")->get();
            $historial = new HistorialEstado(["estado_id"=>$pendiente[0]->id,"observacion_cambio"=>"Creacion de Solicitud"]);


            $solicitud->historialEstado()->save($historial);
            $solicitud->update(['estado'=>$pendiente[0]->id]);

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

            $fecha_venc = BaseCarbon::now()->addMonths(1)->startOfMonth()->add(($solicitud->inicio_cuota-1),'day');

            foreach ($solicitud->historialEstado as $historial) {
                $historial->estadoSolicitud;
            }
            
            $analisis = $this->calculosAnalisis($id);
            $cuotero = $this->calcularCuotero($solicitud->tipoPlazo->id,"12",$solicitud->monto_credito+$solicitud->gastos_administrativos,$fecha_venc);
            $reglas_estado = $solicitud->historialEstado->last()->estadoSolicitud->regla;
            foreach ($reglas_estado as $regla) {
                $regla->estadoPosible;
            }

            // $solicitud->put('analisis', $analisis);

            return ["cod"=>"00","msg"=>"todo correcto","datos"=>["solicitud"=>$solicitud,"analisis"=>$analisis,"cuotero"=>$cuotero['datos'],"reglas"=>$reglas_estado]];
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
            $campos = $this->validate($request,[
                "estado_id"=>'integer',
                "observacion"=>'string'
            ]);
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
                    if(($referencia['nombres_apellido'] == $refDB['nombres_apellido']) && ($referencia['telefono'] == $refDB['telefono'])){
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

            $estado_actual = $solicitud->historialEstado->last()->estado_id;

            if ($estado_actual != $campos["estado_id"] && ($campos["estado_id"] !="") && ($campos["estado_id"] !="0")) {
                if( $this->validarEstado($estado_actual,$campos["estado_id"])) {
                    $historial = new HistorialEstado(["estado_id"=>$campos["estado_id"],"observacion_cambio"=>$campos['observacion']]);
                    $solicitud->historialEstado()->save($historial);
                    $solicitud->update(['estado'=>$campos["estado_id"]]);

                }else{
                    return ["cod"=>"12","msg"=>"Estado no disponible para el cambio"];
                }

            }

            return ["cod"=>"00","msg"=>"Actualización Correcta"];
        } catch( ModelNotFoundException $e){
            return ["cod"=>"04","msg"=>"no existen datos","error"=>$e->getMessage()];
        } catch (\Exception $e) {
            return ["cod"=>"99","msg"=>"no existen datos","error"=>$e->getMessage()];
        }


    }

    public function cambiarEstado(UpdateSolicitudRequest $request,$id){
        $desembolsado = EstadoSolicitud::where("descripcion","APROBADO")->get();
        $solicitud = Solicitud::findOrfail($id);
        $estado_actual = $solicitud->historialEstado->last()->estado_id;
        $campos = $this->validate($request,[
            "estado_id"=>'required|integer',
            "observacion"=>'required|string'
        ]);

        if($this->validarEstado($estado_actual,$campos["estado_id"])) {
            $historial = new HistorialEstado(["estado_id"=>$campos["estado_id"],"observacion_cambio"=>$campos['observacion']]);
            $solicitud->historialEstado()->save($historial);
            $solicitud->update(['estado'=>$campos["estado_id"]]);

            if($campos["estado_id"] == $desembolsado[0]->id){
                $this->guardarCuotero($solicitud);
            }

            return ["cod"=>"00","msg"=>"Cambio de estado realizado Correctamente"];
        }else{
            return ["cod"=>"12","msg"=>"Estado no disponible para el cambio"];

        }

    }

    private function validarEstado($oldEstado,$newEstado){
        $estado = EstadoSolicitud::findOrfail($oldEstado);
        $estado->regla;
        $resp = false;

        foreach ($estado->regla as $regla) {
            if($regla->estado_posible == $newEstado){
                $resp=true;
                break;
            }
        }
         return $resp;
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

    public function filtroSolicitud($estado,$id){

        $string = Str::upper($estado);
        $aprobado = EstadoSolicitud::where("descripcion",$string)->get();
        $id_estado = ['estado'=>$aprobado[0]->id];

        $query=Solicitud::select("solicitud.id","cliente.documento","cliente.nombre","cliente.apellido","cliente.tipo_documento","solicitud.ingresos_actuales","solicitud.monto_credito","solicitud.interes","solicitud.tipo_plazo","solicitud.cant_cuotas","tipo_plazo.descripcion as descripcion_plazo")
        ->join("cliente", "cliente.id", "solicitud.cliente_id","estado_solicitud.descripcion")
        ->join("tipo_plazo", "tipo_plazo.id", "solicitud.tipo_plazo")
        ->where("solicitud.estado",$id_estado['estado'])
        ->where("cliente.id",$id);
        // ->leftJoin('historial_estado', function($query) {
        //     $query->on('solicitud.id','=','historial_estado.solicitud_id')
        //     ->whereRaw('historial_estado.id IN (select MAX(historial_estado.id) from historial_estado as he join solicitud as s on he.solicitud_id = s.id group by s.id)');
        // })
        // ->leftJoin("estado_solicitud","estado_solicitud.id","historial_estado.estado_id")
        ;

        $query = $query->orderBy("cliente.documento")->get();
        if(count($query) != 0){
            return ["cod"=>"00","msg"=>"todo correcto","datos"=>$query];
        }else{
            return ["cod"=>"04","msg"=>"no existen datos"];
        }
    }

    public function calcularCuotero($idPlazo,$cuotas,$monto,$fecha_inicio="0"){
        $fecha_temp = ($fecha_inicio!="0")?$fecha_inicio:"01-06-2023";

        $cuotero = [];
        # Conversion de interes al plazo seleccionado
        $tipoPlazo = TipoPlazo::findOrfail($idPlazo);

        $tasaInteres =  ( ( $tipoPlazo->interes / 100 ) / $tipoPlazo->factor_divisor ) ;

        # factor de amortizacion
        $factor = ($tasaInteres * pow( 1 + $tasaInteres,$cuotas)) / (pow(1 + $tasaInteres, $cuotas) - 1);;

        # Valor de cuota
        $montoCuota = $this->redondearMiles(($monto * $factor));

        # Saldo
        $saldoPendiente = $monto;

        #Proceso para generar cuotero
        for ( $i = 1; $i <= $cuotas ; $i++) {
            $interesCuota = ($this->redondearMiles(($saldoPendiente * $tasaInteres),2));
            $neto = $montoCuota - $interesCuota;
            $saldoPendiente -= ($montoCuota - $interesCuota);
            if($tipoPlazo->id == 4){
                $fecha_temp = date('Y-m-d', strtotime($fecha_temp. ' + 1 month'));
            }else{
                $fecha_temp = date('Y-m-d', strtotime($fecha_temp. ' + '.$tipoPlazo->dias_vencimiento.' days'));
            }

            $cuotero[]=[
                "n_cuota"=> $i,
                "interes"=> round($this->redondearMiles($interesCuota)*1.1,0),
                "neto"=> $this->redondearMiles($neto),
                "saldo"=>  $this->redondearMiles($neto),
                "cuota"=>  $this->redondearMiles($montoCuota),
                "capital"=> max($this->redondearMiles($saldoPendiente),0),
                "vencimiento"=>$fecha_temp
            ];

        }
        return ["cod"=>"00","msg"=>"todo correcto","montoCuota"=>$montoCuota,"datos"=>$cuotero];
    }

    private function guardarCuotero(Solicitud $solicitud){

        $solicitud->tipoPlazo;
        $fecha_venc = BaseCarbon::now()->addMonths(1)->startOfMonth()->add(($solicitud->inicio_cuota-1),'day');

        $cuotero = $this->calcularCuotero($solicitud->tipoPlazo->id,$solicitud->cant_cuotas,$solicitud->monto_credito+$solicitud->gastos_administrativos,$fecha_venc);
        $estado = EstadoCuota::where('descripcion','PENDIENTE')->get()[0];

        //ESTADOCUOTA
        $temp =[];
        foreach ($cuotero['datos'] as  $cuota) {
            $temp[]  = new Cuotas([
                'n_cuota'=>$cuota["n_cuota"],
                'cuota'=>$cuota["cuota"],
                'saldo'=>$cuota["cuota"],
                'interes'=>$cuota["interes"],
                'amortizacion'=>$cuota["neto"],
                'mora'=>"0",
                'capital'=>$cuota["capital"],
                'estado'=>$estado->id,
                'fec_vencimiento'=>$cuota['vencimiento']
            ]);
        }
        $solicitud->cuotas()->saveMany($temp);

    }

    private function redondearMiles($numero){
        $x = ceil(($numero / 1000)) * 1000;
        return $x;
    }

    public function obtenerCuotasPendientes($id){
        try {
            $solicitudes = Solicitud::where('cliente_id','=',$id)->where('estado','5')->get();
            if(count($solicitudes)<1){
                throw  new ModelNotFoundException;
            }

            // return ["cod"=>"00","msg"=>"todo correcto","temp"=>$solicitudes,"cliente"=>$id];
            // foreach ($solicitudes as  $solicitud) {
            //     $this->recalcularCuotas($solicitud);
            // }
            $cuotas = [];
            $cuotasPendientes = [];

            foreach ($solicitudes as  $solictud) {
                $cuotas[] = $solictud->cuotas->filter(function ($item, int $key) {
                    return ($item->estado==1 || $item->estado == 3);
                })->toArray();
            }
            foreach ($cuotas as $cuota) {
                foreach($cuota as $desglose){
                    array_push($cuotasPendientes, $desglose);
                }
            }

            return ["cod"=>"00","msg"=>"todo correcto","datos"=>$cuotasPendientes];
        } catch( ModelNotFoundException $e){
            return ["cod"=>"04","msg"=>"no existen datos","error"=>$e->getMessage()];
        }catch (\Exception $e) {
            return ["cod"=>"99","msg"=>"Error inesperado","datos"=>$e->getMessage()];

        }


    }

    private function recalcularCuotas($solicitud){
        //fecha de hoy
        foreach ($solicitud->cuotas as $cuota) {
            //aplicar la mora
        }
    }
}
