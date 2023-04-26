<?php

use App\Http\Controllers\CartaTrucadaController;
use App\Http\Controllers\ExpedientsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuariController;

Route::get('/', function () { 
    return view('index');
});

Route::get('/login', [UsuariController::class, 'showLogin'])->name('login');
Route::post('/login', [UsuariController::class, 'login']);
Route::get('/logout', [UsuariController::class, 'logout'])->name('logout');
Route::get('/registre',[UsuariController::class, 'showRegistre'])->name('registre');
Route::post('/registre',[UsuariController::class, 'store']);

/* ALL LOGGED */
Route::middleware(['auth'])->group(function() {
    Route::get('/home', function () {
        $user=Auth::user();
        return view('pages.home', compact('user'));
    })->name('home');
    Route::get('/carta', [CartaTrucadaController::class, 'index'])->middleware('only.from.home')->name('carta');
});

Route::middleware(['auth', 'user_type:2,3'])->group(function () {
    Route::get('/operadors', function() {
        return view('pages.operadors');
    })->name('operadors');
    Route::get('/expedients', [ExpedientsController::class, 'index'])->name('expedients');
    // Ruta grafico localizacion
    Route::get('/chartLoc', function () {
        return view('charts.chartIncidentsMunicipi');
    })->name('graphs');
    //Ruta grafico llamadas
    Route::get('/chartLlamada', function () {
        return view('charts.chartLlamadasTipoInci');
    });
});