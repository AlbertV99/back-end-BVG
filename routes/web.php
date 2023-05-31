<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReporteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


//Route::get('movimiento/pdf', [ReporteController::class, 'movimientosCaja']);
Route::get('usuarios/pdf', [ReporteController::class, 'usuarios']);
Route::get('clientes/pdf', [ReporteController::class, 'clientes']);
Route::get('balance/pdf/{anho}', [ReporteController::class, 'balanceMensual']);
Route::get('movimiento/pdf/{fechaInicio}/{fechaFin}', [ReporteController::class, 'movimientosCaja']);
Route::get('estadisticaMov/pdf', [ReporteController::class, 'estadisticaMovimiento']);

