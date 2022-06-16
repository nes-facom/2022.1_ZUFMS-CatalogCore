<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\CollectionController;
use \App\Http\Controllers\AuthController;
use \App\Http\Controllers\UserController;

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

Route::post('/auth/token', [AuthController::class, 'token']);
Route::post('/auth/revoke', [AuthController::class, 'revoke']);
Route::post('/auth/otp', [AuthController::class, 'otp']);

Route::middleware(['token', 'token.scope:users:read'])->group(function () {
    Route::get('/users', [UserController::class, 'getAll']);
    Route::get('/users/{id}', [UserController::class, 'getOne']);
});

Route::middleware(['token', 'token.scope:users'])->group(function () {
    Route::post('/users', [UserController::class, 'createOne']);
    Route::get('/users', [UserController::class, 'getAll']);
    Route::put('/users/{id}', [UserController::class, 'updateOne']);
    Route::delete('/users/{id}', [UserController::class, 'deleteOne']);
    Route::get('/users/{id}', [UserController::class, 'getOne']);
});

Route::middleware(['token', 'token.scope:occurrences:read'])->group(function () {
    Route::get('/occurrences', [CollectionController::class, 'getAll']);
    Route::get('/occurrences/{occurrenceID}', [CollectionController::class, 'getOne']);
    Route::get('/occurrences/autocomplete', [CollectionController::class, 'getAutocomplete']);
    Route::post('/occurrences/file/verify', [CollectionController::class, 'fileVerify']);
});

Route::middleware(['token', 'token.scope:occurrences'])->group(function () {
    Route::post('/occurrences', [CollectionController::class, 'createMany']);
    Route::put('/occurrences', [CollectionController::class, 'updateMany']);
    Route::delete('/occurrences', [CollectionController::class, 'deleteMany']);
    Route::post('/occurrences/file', [CollectionController::class, 'file']);
    Route::post('/occurrences/file/verify', [CollectionController::class, 'uploadDocumentReturnJson']);
    Route::put('/occurrences/{occurrenceID}', [CollectionController::class, 'updateOne']);
    Route::delete('/occurrences/{occurrenceID}', [CollectionController::class, 'deleteOne']);
    Route::post('/occurrences/file', [CollectionController::class, 'file']);
});
