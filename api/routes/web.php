<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthController;
use \App\Http\Controllers\CollectionController;
use \App\Http\Controllers\UserController;
use \App\Http\Controllers\TesteController;
use Ramsey\Collection\Collection;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//teste users
Route::get('/teste/users', [UserController::class, 'getAll']);
Route::post('/teste/users', [UserController::class, 'createOne']);
Route::get('/teste/users/{id}', [UserController::class, 'getOne']);
Route::delete('/teste/users/{id}', [UserController::class, 'deleteOne']);
Route::put('/teste/users/{id}', [UserController::class, 'updateOne']);

Route::post('/teste', [CollectionController::class, 'uploadDocument']);
Route::post('/teste/insertSheetToDatabase', [TesteController::class, 'testeInsertSheetToDatabase']);

Route::post('/api/auth', [AuthController::class, 'authenticate']);

Route::group(['middleware' => ['apiJwt']], function(){
    # Auth
    Route::get('/api/auth/logout', [AuthController::class, 'logout']);


    # User
    Route::post('/api/user/create', [UserController::class, 'createUser'])
        ->middleware(['check.user.roles:admin']);
    Route::delete('/api/user/delete', [UserController::class, 'deleteUser'])
        ->middleware(['check.user.roles:admin']);
    Route::post('/api/acervo/upload-file', [CollectionController::class, 'uploadDocument'])
        ->middleware(['check.user.roles:admin|criador|editor']);
});
