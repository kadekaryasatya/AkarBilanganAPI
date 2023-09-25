<?php

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

// Route::post('/hitung-akar', 'AkarController@hitungAkar');

Route::post('/hitung-akar', [App\Http\Controllers\AkarController::class, 'hitungAkar']);

Route::get('/get-all-data', [App\Http\Controllers\AkarController::class, 'getAllData']);

