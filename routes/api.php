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
use App\Http\Controllers\CajaController;
use App\Http\Controllers\ConceptosCajaController;
use App\Http\Controllers\EstadoCuotaController;
use App\Http\Controllers\OperacionesController;

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

    #ESTADOS CUOTA
    Route::get('/estadoCuota',[EstadoCuotaController::class, 'index']);

    # BARRIO
    Route::controller(BarrioController::class)->group(function () {
        Route::get('/barrio/{pag?}', 'index');
        Route::get('/barrio/u/{id}', 'show');
        Route::post('/barrio/', 'store');
        Route::put('/barrio/{id}', 'update');
        Route::delete('/barrio/{id}', 'destroy');
    });


    #TIPOS PLAZOS
    Route::get('/tipoPlazo/{pag?}',[TipoPlazoController::class, 'index']);
    Route::get('/tipoPlazo/u/{id}',[TipoPlazoController::class, 'show']);
    Route::post('/tipoPlazo/',[TipoPlazoController::class, 'store']);
    Route::put('/tipoPlazo/{id}',[TipoPlazoController::class, 'update']);
    Route::delete('/tipoPlazo/{id}',[TipoPlazoController::class, 'destroy']);

    #SOLICITUDES
    Route::controller(SolicitudController::class)->group(function () {
        Route::get('/solicitud/{estado}/{pag?}/','index');
        Route::get('/solicitudUnico/{id}','show');
        Route::get('/solicitud/cuotero/interes/{idPlazo}/cuotas/{cuotas}/monto/{monto}','calcularCuotero');

        Route::post('/solicitud/','store');
        Route::put('/solicitud/{id}','actualizarReferencias');
        Route::put('/solicitud/{id}/estado','cambiarEstado');
        Route::delete('/solicitud/{id}','destroy');
    });



    #CONCEPTO CAJA
    Route::get('/conceptoCaja/{pag?}',[ConceptosCajaController::class, 'index']);
    Route::get('/conceptoCaja/u/{id}',[ConceptosCajaController::class, 'show']);
    Route::post('/conceptoCaja/',[ConceptosCajaController::class, 'store']);
    Route::put('/conceptoCaja/{id}',[ConceptosCajaController::class, 'update']);
    Route::delete('/conceptoCaja/{id}',[ConceptosCajaController::class, 'destroy']);

    #OPERACIONES
    Route::get('/operaciones/{pag?}',[OperacionesController::class, 'index']);
    Route::get('/operaciones/u/{id}',[OperacionesController::class, 'show']);
    Route::post('/operaciones/',[OperacionesController::class, 'store']);

    #CAJA
    Route::get('/caja/{pag?}',[CajaController::class, 'index']);
    Route::get('/caja/u/{id}',[CajaController::class, 'show']);
    Route::post('/caja/',[CajaController::class, 'store']);
    Route::get('/estado/caja/{id}/{uid}',[CajaController::class, 'estadoCaja']);
    Route::post('/apertura/caja/{id}',[CajaController::class, 'abrirCaja']);
    Route::post('/cierre/caja/{id}',[CajaController::class, 'cerrarCaja']);
    Route::put('/caja/{id}',[CajaController::class, 'update']);
    Route::delete('/caja/{id}',[CajaController::class, 'destroy']);

    #RUTA INEXISTENTE
    Route::fallback(function () {
        return ["cod"=>"99","msg"=>"Error general"];
    });

});
// Route::apiResource('cliente', ClienteController::class);



// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
