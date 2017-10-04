<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('logout', 'Api\MasterController@logoutAPI')->name('api.logout');

Route::get('news', 'Api\MasterController@listNews')->name('api.news.list');
Route::get('news/{id}', 'Api\MasterController@detailNews')->name('api.news.detail');
Route::get('banners', 'Api\MasterController@listBanner')->name('api.banner.list');

Route::get('spk', 'Api\OrderController@list')->name('api.spk.list');
Route::get('sync', 'Api\OrderController@syncSpk')->name('api.spk.sync');
Route::get('spk/field', 'Api\OrderController@fields')->name('api.spk.field');
Route::get('spk/{id}', 'Api\OrderController@detail')->name('api.order.detail');
Route::post('spk', 'Api\OrderController@post')->name('api.order.create');
Route::put('spk/{id}', 'Api\OrderController@put')->name('api.order.update');

Route::get('simulation', 'Api\SimulationController@list')->name('api.simulation.list');
Route::get('simulation/{id}', 'Api\SimulationController@detail')->name('api.simulation.detail');
Route::post('simulation', 'Api\SimulationController@store')->name('api.simulation.create');
Route::put('simulation/{id}', 'Api\SimulationController@update')->name('api.simulation.update');

Route::post('do', 'Api\OrderController@doPost')->name('api.order.do');
Route::get('insentif', 'Api\OrderController@insentif')->name('api.insentif');
Route::get('leasing-formula', 'Api\OrderController@leasingFormula')->name('api.leasingFormula');
Route::get('customer', 'Api\OrderController@customerData')->name('api.customerData');