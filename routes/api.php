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
            Route::controller(App\Http\Controllers\Admin\CompetitionController::class)->group(function () {
                Route::get('/competition', 'index');
                Route::post('/competition/store', 'store');
                Route::get('/competition/{id}', 'show');
                Route::post('/competition/update/{id}', 'update');
                Route::post('/competition/delete/{id}', 'destroy');
            });
            Route::controller(App\Http\Controllers\Admin\CompetitionLevelMasterController::class)->group(function () {
                Route::get('/competition_level_masters', 'index');
                Route::post('/competition_level_masters/store', 'store');
                Route::get('/competition_level_masters/{id}', 'show');
                Route::post('/competition_level_masters/update/{id}', 'update');
                Route::post('/competition_level_masters/delete/{id}', 'destroy');
            });
            Route::controller(App\Http\Controllers\Admin\CompetitionTypeMasterController::class)->group(function () {
                Route::get('/competition_type_masters', 'index');
                Route::post('/competition_type_masters/store', 'store');
                Route::get('/competition_type_masters/{id}', 'show');
                Route::post('/competition_type_masters/update/{id}', 'update');
                Route::post('/competition_type_masters/delete/{id}', 'destroy');
            });
            Route::controller(App\Http\Controllers\Admin\CompetitionLevelDetailController::class)->group(function () {
                Route::get('/competition_level_details', 'index');
                Route::post('/competition_level_details/store', 'store');
                Route::get('/competition_level_details/{id}', 'show');
                Route::post('/competition_level_details/update/{id}', 'update');
                Route::post('/competition_level_details/delete/{id}', 'destroy');
            });
        });
        Route::group(['prefix'=>'org'], function(){
            Route::get('/me', function(Request $request){
                return $request->user();
            })->name('api.org.me');
        });
    });
});