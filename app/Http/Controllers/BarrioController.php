<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorebarrioRequest;
use App\Http\Requests\UpdatebarrioRequest;
use App\Models\barrio;

class BarrioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StorebarrioRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorebarrioRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\barrio  $barrio
     * @return \Illuminate\Http\Response
     */
    public function show(barrio $barrio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\barrio  $barrio
     * @return \Illuminate\Http\Response
     */
    public function edit(barrio $barrio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatebarrioRequest  $request
     * @param  \App\Models\barrio  $barrio
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatebarrioRequest $request, barrio $barrio)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\barrio  $barrio
     * @return \Illuminate\Http\Response
     */
    public function destroy(barrio $barrio)
    {
        //
    }
}
