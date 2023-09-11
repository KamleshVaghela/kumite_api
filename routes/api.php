<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::controller(App\Http\Controllers\EncryptionController::class)->group(function () {
    Route::post('/encrypt_data', 'encrypt_data');
    Route::post('/decrypt_data', 'decrypt_data');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['before_request'])->group(function() {
    Route::controller(App\Http\Controllers\UserController::class)->group(function () {
        Route::post('/register', 'register');
        Route::post('/login', 'login');
        Route::post('/logout','logout');
    });
    Route::middleware(['auth:sanctum'])->group(function() {
        Route::group(['prefix'=>'admin'], function(){
            Route::get('/me', function(Request $request){
                return $request->user();
            })->name('api.admin.me');
        });
        Route::group(['prefix'=>'org'], function(){
            Route::get('/me', function(Request $request){
                return $request->user();
            })->name('api.org.me');
        });
    });
});