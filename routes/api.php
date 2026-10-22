<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

Route::get('/', function () {
    return response()->json([
        'success' => true,
        'message' => 'API en Funcionamiento'
    ], 200);
});


Route::post('/', function (Request $request) {

    $nombre = $request->input('nombre');
    $email = $request->input('email');

    Log::info($nombre);
    Log::info($email);

    return response()->json([
        'nombre' => $nombre,
        'email' => $email
    ], 200);
});
