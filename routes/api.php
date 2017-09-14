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

Route::get('news', 'Api\MasterController@listNews')->name('api.news.list');
Route::get('news/{id}', 'Api\MasterController@detailNews')->name('api.news.detail');
Route::get('banners', 'Api\MasterController@listBanner')->name('api.banner.list');

Route::get('spk', 'Api\OrderController@list')->name('api.spk.list');
Route::get('spk/{id}', 'Api\OrderController@detail')->name('api.order.detail');
Route::post('spk', 'Api\OrderController@post')->name('api.order.create');
Route::put('spk/{id}', 'Api\OrderController@put')->name('api.order.update');

Route::post('do', 'Api\OrderController@doPost')->name('api.order.do');
Route::get('insentif', 'Api\OrderController@insentif')->name('api.insentif');