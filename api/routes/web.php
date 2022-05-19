<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthController;
use \App\Http\Controllers\AcervoController;
use \App\Http\Controllers\UserController;
use \App\Http\Controllers\TesteController;

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

Route::post('/teste', [TesteController::class, 'teste']);
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
    Route::post('/api/acervo/upload-file', [AcervoController::class, 'uploadDocument'])
        ->middleware(['check.user.roles:admin|criador|editor']);
});
