<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\BarrioController;
use App\Http\Controllers\EstadoCivilController;

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

    # ESTADO CIVIL
    Route::get('/estadoCivil/{pag?}',[EstadoCivilController::class, 'index']);

    # BARRIO
    Route::get('/barrio/{pag?}',[BarrioController::class, 'index']);
    Route::get('/barrio/u/{id}',[BarrioController::class, 'show']);
    Route::post('/barrio/',[BarrioController::class, 'store']);
    Route::put('/barrio/{id}',[BarrioController::class, 'update']);
    Route::delete('/barrio/{id}',[BarrioController::class, 'destroy']);
});
// Route::apiResource('cliente', ClienteController::class);



// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
