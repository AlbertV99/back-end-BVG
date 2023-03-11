<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEstadoSolicitudRequest;
use App\Http\Requests\UpdateEstadoSolicitudRequest;
use App\Models\EstadoSolicitud;

class EstadoSolicitudController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id=0){
        $query = EstadoSolicitud::select("estado_solicitud.id","eestado_solicitud.escripcion");
        if($id!=0){
            $query->join('reglas_estados','reglas_estados.estado_regla','estado_solicitud.id');
        }
        return ["cod"=>"00","msg"=>"todo correcto","datos"=>$query->get()];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreEstadoSolicitudRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEstadoSolicitudRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EstadoSolicitud  $estadoSolicitud
     * @return \Illuminate\Http\Response
     */
    public function show(EstadoSolicitud $estadoSolicitud)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EstadoSolicitud  $estadoSolicitud
     * @return \Illuminate\Http\Response
     */
    public function edit(EstadoSolicitud $estadoSolicitud)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEstadoSolicitudRequest  $request
     * @param  \App\Models\EstadoSolicitud  $estadoSolicitud
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEstadoSolicitudRequest $request, EstadoSolicitud $estadoSolicitud)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EstadoSolicitud  $estadoSolicitud
     * @return \Illuminate\Http\Response
     */
    public function destroy(EstadoSolicitud $estadoSolicitud)
    {
        //
    }
}
