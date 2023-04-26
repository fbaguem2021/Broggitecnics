<?php

use App\Http\Controllers\Api\CartaData;
use App\Http\Controllers\Api\CartaTrucadaController;
use App\Http\Controllers\Api\ChartController;
use App\Http\Controllers\Api\ChartCallController;
use App\Http\Controllers\Api\ExpedientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AgenciaController;
use App\Http\Controllers\Api\CartaHasAgencies;
use App\Http\Controllers\Api\CartaTrucadaHasAgencia;
use App\Http\Controllers\Api\EstatAgencies;
use App\Http\Controllers\Api\UsuariController;
use App\Http\Controllers\Api\EstatExpedientController;
use App\Models\EstatAgencia;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/* USUARIS */
Route::get('usuari-buscar', [UsuariController::class, 'buscar'])->name('usuari.buscar');
Route::get('usuari-buscar-id', [UsuariController::class, 'buscarId'])->name('usuari.buscar.id');
Route::apiResource('usuari', UsuariController::class);

/* EXPEDIENTS */
Route::get('expedients/{filter}', [ExpedientController::class, 'index']);
Route::get('expedients-gestio/{filter}/{value?}/{dir?}', [ExpedientController::class, 'indexGestio']);
Route::get('expedient/{id}', [ExpedientController::class, 'show']);
Route::post('/expedient', [ExpedientController::class, 'store']);
Route::apiResource('/estatExpedient', EstatExpedientController::class);

/* AGENCIES */
Route::apiResource('agencies', AgenciaController::class);
Route::apiResource('estatAgencies', EstatAgencies::class);
Route::put('updateEstatAgencia/{cartaTrucadaId}/{agenciaId}', [EstatAgencies::class, 'updateState']);

/* GRÀFICS */
Route::get('/incidentesChart', [ChartController::class, 'index']);
Route::get('/llamadasChart', [ChartCallController::class, 'index']);

/* CARTES TRUCADA */
Route::apiResource('/cartesTrucades', CartaTrucadaController::class);
Route::get('/cartaData', [CartaData::class, 'index']);

/* INTERLOCUTORS */
Route::get('/interlocutorCheck/{number}', [CartaData::class, 'checkNumber']);
Route::get('/interlocutorGenerate', [CartaData::class, 'generateNumber']);
Route::post('/postInterlocutor', [CartaTrucadaController::class, 'storeInterlocutor']);