<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AcervoController;
use \App\Http\Controllers\AuthController;

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

Route::get('/occurrences/autocomplete', [AcervoController::class, 'getAutocomplete']);

Route::post('/auth/token', [AuthController::class, 'token']);

Route::post('/auth/revoke', [AuthController::class, 'revoke']);

Route::post('/auth/otp', [AuthController::class, 'otp']);
