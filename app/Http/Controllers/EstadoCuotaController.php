<?php

namespace App\Http\Controllers;

use App\Models\EstadoCuota;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEstadoCuotaRequest;
use App\Http\Requests\UpdateEstadoCuotaRequest;

class EstadoCuotaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = EstadoCuota::select("id","descripcion");

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
     * @param  \App\Http\Requests\StoreEstadoCuotaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEstadoCuotaRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EstadoCuota  $estadoCuota
     * @return \Illuminate\Http\Response
     */
    public function show(EstadoCuota $estadoCuota)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EstadoCuota  $estadoCuota
     * @return \Illuminate\Http\Response
     */
    public function edit(EstadoCuota $estadoCuota)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEstadoCuotaRequest  $request
     * @param  \App\Models\EstadoCuota  $estadoCuota
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEstadoCuotaRequest $request, EstadoCuota $estadoCuota)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EstadoCuota  $estadoCuota
     * @return \Illuminate\Http\Response
     */
    public function destroy(EstadoCuota $estadoCuota)
    {
        //
    }
}
