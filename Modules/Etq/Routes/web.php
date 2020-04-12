<?php

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

Route::prefix('etq')->group(function() {
    Route::get('etq-tasks/estoque', 'EtqTaskEstoqueController@tasks' )->name('etq-tasks-estoque');
    Route::get('etq-tasks/estoque/{date}', 'EtqTaskEstoqueController@tasksByDate' )->name('etq-tasks-estoque-date');
    Route::get('etq-estoque/excel/inventario', 'EtqEstoqueController@excelByDate' )->name('etq-stock-excel');
    Route::get('etq-estoque/', 'EtqEstoqueController@index' )->name('etq-stock');
    Route::post('etq-estoque-dados', 'EtqEstoqueController@data' )->name('etq-estoque-dados');

    Route::get('etq-estoque/filtrar', 'EtqEstoqueExportController@filter' )->name('etq-estoque-filter');
    Route::post('etq-estoque/filtrar', 'EtqEstoqueExportController@exportData' )->name('etq-estoque-export');
    Route::post('etq-estoque/filtrar/firmas', 'EtqEstoqueExportController@loadData' )->name('etq-estoque-load');


});
