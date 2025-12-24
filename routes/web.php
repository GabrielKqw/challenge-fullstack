<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'API de Produtos - Challenge Fullstack',
        'version' => '1.0.0'
    ]);
});

