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

Route::get('master/banner/change-status/{id}/{status}', 'Master\BannerController@changeStatus')->name('banner.change-status');
Route::resource('banner', 'Master\BannerController');

Route::get('master/news/change-status/{id}/{status}', 'Master\NewsController@changeStatus')->name('news.change-status');
Route::resource('news', 'Master\NewsController');

Route::get('master/car-category/change-status/{id}/{status}', 'Master\CarCategoryController@changeStatus')->name('car-category.change-status');
Route::resource('car-category', 'Master\CarCategoryController');

Route::get('master/car-model/change-status/{id}/{status}', 'Master\CarModelController@changeStatus')->name('car-model.change-status');
Route::resource('car-model', 'Master\CarModelController');

Route::get('master/car-type/change-status/{id}/{status}', 'Master\CarTypeController@changeStatus')->name('car-type.change-status');
Route::resource('car-type', 'Master\CarTypeController');

Route::get('master/company/change-status/{id}/{status}', 'Master\CompanyController@changeStatus')->name('company.change-status');
Route::resource('company', 'Master\CompanyController');

Route::get('master/dealer/change-status/{id}/{status}', 'Master\DealerController@changeStatus')->name('dealer.change-status');
Route::resource('dealer', 'Master\DealerController');

Route::get('master/customer/change-status/{id}/{status}', 'Master\CustomerController@changeStatus')->name('customer.change-status');
Route::resource('customer', 'Master\CustomerController');

Route::get('master/user/change-status/{id}/{status}', 'Master\UserController@changeStatus')->name('user.change-status');
Route::resource('user', 'Master\UserController');

Route::get('master/bbn/change-status/{id}/{status}', 'Master\BbnController@changeStatus')->name('bbn.change-status');
Route::resource('bbn', 'Master\BbnController');

Route::get('master/job-position/change-status/{id}/{status}', 'Master\JobPositionController@changeStatus')->name('job-position.change-status');
Route::resource('job-position', 'Master\JobPositionController');

Route::get('master/leasing/change-status/{id}/{status}', 'Master\LeasingController@changeStatus')->name('leasing.change-status');
Route::resource('leasing', 'Master\LeasingController');

Route::resource('order', 'Spk\OrderController');

Route::get('setting/approval/delete/{id}', 'Setting\ApprovalController@delete')->name('approval.delete');
Route::put('setting/approval/change-level', 'Setting\ApprovalController@changeLevel')->name('approval.change-level');
Route::resource('setting/approval', 'Setting\ApprovalController');

Route::get('logout', 'Auth\LoginController@logout');

Route::get('/', 'Spk\OrderController@index');
