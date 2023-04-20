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
    Route::controller(ClienteController::class)->group(function () {
        Route::get('/cliente/{pag?}', 'index');
        Route::get('/cliente/u/{id}', 'show');
        Route::post('/cliente/', 'store');
        Route::put('/cliente/{id}', 'update');
        Route::delete('/cliente/{id}', 'destroy');
    });


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
    Route::controller(SolicitudController::class)->group(function () {
        Route::get('/tipoPlazo/{pag?}','index');
        Route::get('/tipoPlazo/u/{id}','show');
        Route::post('/tipoPlazo/','store');
        Route::put('/tipoPlazo/{id}','update');
        Route::delete('/tipoPlazo/{id}','destroy');
    });

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
    Route::controller(ConceptosCajaController::class)->group(function () {
        Route::get('/conceptoCaja/{pag?}','index');
        Route::get('/conceptoCaja/u/{id}','show');
        Route::post('/conceptoCaja/','store');
        Route::put('/conceptoCaja/{id}','update');
        Route::delete('/conceptoCaja/{id}','destroy');
    }

    #OPERACIONES
    Route::controller(OperacionesController::class)->group(function () {
        Route::get('/operaciones/{pag?}', 'index');
        Route::get('/operaciones/u/{id}', 'show');
        Route::post('/operaciones/', 'store');

    });

    #CAJA
    Route::controller(CajaController::class)->group(function () {
        Route::get('/caja/{pag?}', 'index');
        Route::get('/caja/u/{id}', 'show');
        Route::post('/caja/', 'store');
        Route::get('/estado/caja/{id}/{uid}', 'estadoCaja');
        Route::post('/apertura/caja/{id}', 'abrirCaja');
        Route::post('/cierre/caja/{id}', 'cerrarCaja');
        Route::put('/caja/{id}', 'update');
        Route::delete('/caja/{id}', 'destroy');
    ]);

    #RUTA INEXISTENTE
    Route::fallback(function () {
        return ["cod"=>"99","msg"=>"Error general"];
    });

});
// Route::apiResource('cliente', ClienteController::class);



// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
