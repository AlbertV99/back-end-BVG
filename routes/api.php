<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\BarrioController;
use App\Http\Controllers\EstadoCivilController;
use App\Http\Controllers\EstadoSolicitudController;
use App\Http\Controllers\TipoDocumentoController;
use App\Http\Controllers\TipoPlazoController;
use App\Http\Controllers\SolicitudController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['cors'])->group(function () {
    #CLIENTE
    Route::get('/cliente/{pag?}',[ClienteController::class, 'index']);
    Route::get('/cliente/u/{id}',[ClienteController::class, 'show']);
    Route::post('/cliente/',[ClienteController::class, 'store']);
    Route::put('/cliente/{id}',[ClienteController::class, 'update']);
    Route::delete('/cliente/{id}',[ClienteController::class, 'destroy']);

    # PERFIL CLIENTE

    Route::get('/perfilCliente/{id}',[ClienteController::class, 'obtenerPerfil']);

    # ESTADO CIVIL
    Route::get('/estadoCivil/{pag?}',[EstadoCivilController::class, 'index']);

    # ESTADOS SOLICITUD
    Route::get('/estadoSolicitud',[EstadoSolicitudController::class, 'index']);

    # ESTADOS SOLICITUD
    Route::get('/tipoDocumento',[TipoDocumentoController::class, 'index']);

    # BARRIO
    Route::get('/barrio/{pag?}',[BarrioController::class, 'index']);
    Route::get('/barrio/u/{id}',[BarrioController::class, 'show']);
    Route::post('/barrio/',[BarrioController::class, 'store']);
    Route::put('/barrio/{id}',[BarrioController::class, 'update']);
    Route::delete('/barrio/{id}',[BarrioController::class, 'destroy']);

    #TIPOS PLAZOS
    Route::get('/tipoPlazo/{pag?}',[TipoPlazoController::class, 'index']);
    Route::get('/tipoPlazo/u/{id}',[TipoPlazoController::class, 'show']);
    Route::post('/tipoPlazo/',[TipoPlazoController::class, 'store']);
    Route::put('/tipoPlazo/{id}',[TipoPlazoController::class, 'update']);
    Route::delete('/tipoPlazo/{id}',[TipoPlazoController::class, 'destroy']);

    #SOLICITUDES
    Route::get('/solicitud/{estado}/{pag?}/',[SolicitudController::class, 'index']);
    Route::get('/solicitud/u/{id}',[SolicitudController::class, 'show']);
    Route::post('/solicitud/',[SolicitudController::class, 'store']);
    Route::put('/solicitud/{id}',[SolicitudController::class, 'update']);
    Route::delete('/solicitud/{id}',[SolicitudController::class, 'destroy']);

});
// Route::apiResource('cliente', ClienteController::class);



// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
