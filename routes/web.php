<?php

use App\Http\Controllers\CartaTrucadaController;
use App\Http\Controllers\ExpedientsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuariController;

Route::get('/', function () { 
    return view('index');
});

Route::get('agenciasMapa', function(){
    return view('AgenciasMapa.index');
});



Route::get('/login', [UsuariController::class, 'showLogin'])->name('login');
Route::post('/login', [UsuariController::class, 'login']);
Route::get('/logout', [UsuariController::class, 'logout'])->name('logout');
Route::get('/registre',[UsuariController::class, 'showRegistre'])->name('registre');
Route::post('/registre',[UsuariController::class, 'store']);

Route::middleware(['auth'])->group(function() {
    Route::get('/home', function () {
        $user=Auth::user();
        return view('pages.home', compact('user'));
    })->name('home');
    Route::get('/operadors', function() {
        $numero = 5;
        return view('operadors', compact('numero'));
    })->name('operadors');
});

Route::get('/carta', [CartaTrucadaController::class, 'index']);

Route::get('/operadors', function () {
    return view('operadors');
});

Route::get('/expedients', [ExpedientsController::class, 'index']);


Route::get('bootstrap', function () {
    return view('bootstrap');
});