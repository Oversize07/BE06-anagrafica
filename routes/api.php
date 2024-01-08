<?php

use App\Http\Controllers\CittadinoController;
use App\Http\Controllers\FamigliaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// CITTADINO
Route::get('/cittadini', [CittadinoController::class,'index']);
Route::post('/cittadini', [CittadinoController::class,'store']);
Route::patch('/cittadini', [CittadinoController::class,'update']);
Route::delete('/cittadini/{id}', [CittadinoController::class,'destroy']);

// FAMIGLIA
Route::get('/famiglie', [FamigliaController::class,'index']);
Route::post('/famiglie', [FamigliaController::class,'store']);
Route::patch('/famiglie', [FamigliaController::class,'update']);
Route::delete('/famiglie/{id}', [FamigliaController::class,'destroy']);

// richieste 
Route::patch('/famiglie/{famiglia_id}/{cittadino_id}', [FamigliaController::class,'promozioneResponsabileFamiglia']);
Route::patch('/famiglie/{famiglia_id_partenza}/{famiglia_id_destinazione}/{cittadino_id}', [FamigliaController::class,'spostamentoCittadinoDaFamiglia']);
Route::delete('/famiglie/{famiglia_id}/{cittadino_id}', [FamigliaController::class,'rimozioneCittadinoDaFamiglia']);
Route::post('/famiglie/{famiglia_id}/{cittadino_id}', [FamigliaController::class,'associazioneCittadinoAPiuFamiglie']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
