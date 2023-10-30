<?php

use App\Http\Controllers\AuthController;
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

Route::get('/get-lowest-processing-time', [App\Http\Controllers\AkarController::class, 'getLowestProcessingTime']);

Route::get('/get-highest-processing-time', [App\Http\Controllers\AkarController::class, 'getHighestProcessingTime']);

Route::get('/akar-bilangan/by-user', [App\Http\Controllers\AkarController::class,'getDataByUserId']);

Route::get('/average-processing-time', [App\Http\Controllers\AkarController::class,'getAverageProcessingTime']);

Route::get('/user-data', [App\Http\Controllers\AkarController::class, 'getUserData']);


Route::post('/login', [AuthController::class, 'login']);


Route::get('/get-all-users', [AuthController::class, 'getAllUser']);
