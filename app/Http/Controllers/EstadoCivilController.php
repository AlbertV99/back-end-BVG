<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEstadoCivilRequest;
use App\Http\Requests\UpdateEstadoCivilRequest;
use App\Models\EstadoCivil;

class EstadoCivilController extends Controller{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){

            $query = EstadoCivil::select("id","descripcion");

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
     * @param  \App\Http\Requests\StoreEstadoCivilRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEstadoCivilRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EstadoCivil  $estadoCivil
     * @return \Illuminate\Http\Response
     */
    public function show(EstadoCivil $estadoCivil)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EstadoCivil  $estadoCivil
     * @return \Illuminate\Http\Response
     */
    public function edit(EstadoCivil $estadoCivil)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEstadoCivilRequest  $request
     * @param  \App\Models\EstadoCivil  $estadoCivil
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEstadoCivilRequest $request, EstadoCivil $estadoCivil)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EstadoCivil  $estadoCivil
     * @return \Illuminate\Http\Response
     */
    public function destroy(EstadoCivil $estadoCivil)
    {
        //
    }
}
