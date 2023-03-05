<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;

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

// Route::apiResource('cliente', ClienteController::class);
Route::get('/cliente/{pag?}',[ClienteController::class, 'index']);
Route::get('/cliente/u/{id}',[ClienteController::class, 'show']);
Route::post('/cliente/',[ClienteController::class, 'store']);
Route::put('/cliente/{id}',[ClienteController::class, 'update']);
Route::delete('/cliente/{id}',[ClienteController::class, 'destroy']);



// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
