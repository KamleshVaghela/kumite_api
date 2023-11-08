<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome')->name('welcome');
});
// Route::get('/login', [App\Http\Controllers\UserController::class, "web_login"]);
Route::group(['middleware' => 'web'], function () {
    Route::controller(App\Http\Controllers\UserController::class)->group(function () {
        Route::get('/login', 'web_login')->name('login');;
        Route::post('/user/login', 'web_user_login')->name('user.web.login');
    });
    Route::group(['prefix'=>'admin',  'middleware' => 'auth:sanctum'], function() {
        Route::controller(App\Http\Controllers\Admin\HomeController::class)->group(function () {
            Route::get('/dashboard', 'index')->name('admin.dashboard');
        });
        Route::controller(App\Http\Controllers\Admin\SchoolMasterController::class)->group(function () {
            Route::get('/school_master', 'index')->name('admin.school_master');
            Route::post('/school_master/report', 'report')->name('admin.school_master.report');
            Route::get('/school_master/create', 'create')->name('admin.school_master.create');
            Route::post('/school_master/store', 'store')->name('admin.school_master.store');
        });

        Route::controller(App\Http\Controllers\Admin\CompetitionController::class)->group(function () {
            Route::get('/competition', 'index')->name('admin.competition');
            Route::get('/competition/create', 'create')->name('admin.competition.create');
            Route::get('/competition/change_level/{level}', 'changeLevel')->name('admin.competition.change_level');
            Route::get('/competition/board/{encrypted_comp_id}', 'boardIndex')->name('admin.board');
            Route::get('/competition/board/{encrypted_comp_id}/report', 'boardReport')->name('admin.board.report');

            Route::get('/competition/board/{encrypted_comp_id}/competition_details', 'competitionDetails')->name('admin.board.competition_details');
            Route::post('/competition/board/{encrypted_comp_id}/competition_details', 'saveCompetitionDetails')->name('admin.board.save_competition_details');

            Route::get('/competition/board/{encrypted_comp_id}/important_dates', 'importantDates')->name('admin.board.important_dates');
            Route::post('/competition/board/{encrypted_comp_id}/important_dates', 'saveImportantDates')->name('admin.board.save_important_dates');

            Route::get('/competition/board/{encrypted_comp_id}/fees_details', 'feesDetails')->name('admin.board.fees_details');
            Route::post('/competition/board/{encrypted_comp_id}/fees_details', 'saveFeesDetails')->name('admin.board.save_fees_details');

            Route::get('/competition/board/{encrypted_comp_id}/level_details', 'levelDetails')->name('admin.board.level_details');
            Route::post('/competition/board/{encrypted_comp_id}/level_details', 'saveLevelDetails')->name('admin.board.save_level_details');

            Route::get('/competition/board/{encrypted_comp_id}/result_details', 'resultDetails')->name('admin.board.result_details');
            // Route::post('/competition/board/{encrypted_comp_id}/result_details', 'saveResultDetails')->name('admin.board.save_result_details');

            Route::get('/competition/board/{encrypted_comp_id}/bout_details', 'boutDetails')->name('admin.board.bout_details');
            Route::post('/competition/board/{encrypted_comp_id}/bout_details', 'saveBoutDetails')->name('admin.board.save_bout_details');

            Route::get('/competition/board/{encrypted_comp_id}/clear_data', 'clearData')->name('admin.board.clear_data');
            Route::post('/competition/board/{encrypted_comp_id}/clear_data', 'saveClearData')->name('admin.board.save_clear_data');

            Route::get('/competition/board/{encrypted_comp_id}/export_excel', 'exportExcel')->name('admin.board.export_excel');
            Route::get('/competition/board/{encrypted_comp_id}/import_excel', 'importExcel')->name('admin.board.import_excel');
            Route::post('/competition/board/{encrypted_comp_id}/post_import_excel', 'postImportExcel')->name('admin.board.post_import_excel');

            Route::post('/competition/report', 'report')->name('admin.competition.report');
            Route::post('/competition/store', 'store')->name('admin.competition.store');
            Route::get('/competition/edit/{id}', 'edit')->name('admin.competition.edit');
            Route::post('/competition/update/{id}', 'update')->name('admin.competition.update');
        });
        Route::controller(App\Http\Controllers\Admin\CompetitionBoutController::class)->group(function () {
            Route::get('/competition/board/{encrypted_comp_id}/bout/index', 'index')->name('admin.board.bout');
            Route::get('/competition/board/{encrypted_comp_id}/bout/report', 'report')->name('admin.board.bout.report');
            Route::get('/competition/board/{encrypted_comp_id}/bout/{bout_id}/{custom_bout_id}/download_all_bout', 'download_all_bout')->name('admin.board.bout.download_all_bout');
            Route::get('/competition/board/{encrypted_comp_id}/bout/data_table', 'data_table')->name('admin.board.bout.data_table');
            Route::get('/competition/board/{encrypted_comp_id}/bout/data_table/report', 'data_table_report')->name('admin.board.bout.data_table.report');

            Route::get('/competition/board/{encrypted_comp_id}/bout/{bout_id}/{custom_bout_id}/participants', 'participants')->name('admin.board.bout.participants');
            Route::get('/competition/board/{encrypted_comp_id}/bout/{bout_id}/{custom_bout_id}/download_bout', 'download_bout')->name('admin.board.bout.download_bout');
            Route::get('/competition/board/{encrypted_comp_id}/bout/{bout_id}/{custom_bout_id}/{participant_id}/karate_ka', 'karate_ka')->name('admin.board.bout.karate_ka');
            Route::post('/competition/board/{encrypted_comp_id}/bout/{bout_id}/{custom_bout_id}/{participant_id}/save_data', 'save_data')->name('admin.board.bout.save_data');

            Route::get('/competition/board/{encrypted_comp_id}/bout/create', 'create')->name('admin.board.bout.create');
            Route::post('/competition/board/{encrypted_comp_id}/bout/store', 'store')->name('admin.board.bout.save');
            Route::post('/competition/board/{encrypted_comp_id}/bout/{encrypted_bout_id}/show', 'show')->name('admin.board.bout.show');
            Route::post('/competition/board/{encrypted_comp_id}/bout/{encrypted_bout_id}/edit', 'edit')->name('admin.board.bout.edit');
            Route::post('/competition/board/{encrypted_comp_id}/bout/{encrypted_bout_id}/update', 'update')->name('admin.board.bout.update');
            Route::post('/competition/board/{encrypted_comp_id}/bout/{encrypted_bout_id}/destroy', 'destroy')->name('admin.board.bout.destroy');
        });

        Route::controller(App\Http\Controllers\Admin\DefaultCategoryController::class)->group(function () {
            Route::get('/default_category/index', 'index')->name('admin.default_category');
            Route::get('/default_category/report', 'report')->name('admin.default_category.report');

            Route::get('/default_category/{category}/categories', 'categories')->name('admin.default_category.categories');
            Route::get('/default_category/{category_id}/category', 'category')->name('admin.default_category.category');

            // Route::get('/competition/default_category/create', 'create')->name('admin.board.bout.create');
            // Route::post('/competition/default_category/store', 'store')->name('admin.board.bout.save');

            // Route::post('/competition/default_category/{encrypted_bout_id}/show', 'show')->name('admin.board.bout.show');
            // Route::post('/competition/default_category/{encrypted_bout_id}/edit', 'edit')->name('admin.board.bout.edit');
            // Route::post('/competition/default_category/{encrypted_bout_id}/update', 'update')->name('admin.board.bout.update');
            // Route::post('/competition/default_category/{encrypted_bout_id}/destroy', 'destroy')->name('admin.board.bout.destroy');
        });
    });
});

/*Excel import export*/
Route::controller(App\Http\Controllers\ImportExportController::class)->group(function () {
    Route::get('export', 'export')->name('export');
    Route::get('importExportView', 'importExportView');
    Route::post('import', 'import')->name('import');
});