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

Auth::routes();

Route::get('ajax/interest', 'Spk\AjaxController@getLeasingFormula')->name('ajax.getLeasingFormula');
Route::get('ajax/car-type', 'Spk\AjaxController@getCarType')->name('ajax.getCarType');
Route::get('ajax/customer', 'Spk\AjaxController@getCustomerData')->name('ajax.getCustomerData');
Route::get('ajax/do-graph', 'Spk\AjaxController@getGraphDO')->name('ajax.getGraphDO');
Route::get('ajax/spk-graph', 'Spk\AjaxController@getGraphSPK')->name('ajax.getGraphSPK');

Route::get('report/insentif', 'Insentif\ReportController@insentif')->name('report.insentif');
Route::get('report/order', 'Insentif\ReportController@order')->name('report.order');
Route::get('report/do', 'Insentif\ReportController@delivery')->name('report.delivery');

Route::get('logout', 'Auth\LoginController@logout');

Route::get('/', 'DashboardController@doGraph');
