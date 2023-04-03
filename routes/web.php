<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () { 
    return view('index');
});
Route::get('bootstrap', function () {
    return view('bootstrap');
});
Route::get('agenciasMapa', function(){
    return view('AgenciasMapa.index');
});


?>