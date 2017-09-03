<?php 

Route::get('master/banner/change-status/{id}/{status}', 'Master\BannerController@changeStatus')->name('banner.change-status');
Route::resource('master/banner', 'Master\BannerController');

Route::get('master/news/change-status/{id}/{status}', 'Master\NewsController@changeStatus')->name('news.change-status');
Route::resource('master/news', 'Master\NewsController');

Route::get('master/car-category/change-status/{id}/{status}', 'Master\CarCategoryController@changeStatus')->name('car-category.change-status');
Route::resource('master/car-category', 'Master\CarCategoryController');

Route::get('master/car-model/change-status/{id}/{status}', 'Master\CarModelController@changeStatus')->name('car-model.change-status');
Route::resource('master/car-model', 'Master\CarModelController');

Route::get('master/car-type/change-status/{id}/{status}', 'Master\CarTypeController@changeStatus')->name('car-type.change-status');
Route::resource('master/car-type', 'Master\CarTypeController');

Route::get('master/company/change-status/{id}/{status}', 'Master\CompanyController@changeStatus')->name('company.change-status');
Route::resource('master/company', 'Master\CompanyController');

Route::get('master/dealer/change-status/{id}/{status}', 'Master\DealerController@changeStatus')->name('dealer.change-status');
Route::resource('master/dealer', 'Master\DealerController');

Route::get('master/customer/change-status/{id}/{status}', 'Master\CustomerController@changeStatus')->name('customer.change-status');
Route::resource('master/customer', 'Master\CustomerController');

Route::get('master/bbn/change-status/{id}/{status}', 'Master\BbnController@changeStatus')->name('bbn.change-status');
Route::resource('master/bbn', 'Master\BbnController');

Route::get('master/credit-month/change-status/{id}/{status}', 'Master\CreditMonthController@changeStatus')->name('credit-month.change-status');
Route::resource('master/credit-month', 'Master\CreditMonthController');

Route::get('master/area/change-status/{id}/{status}', 'Master\AreaController@changeStatus')->name('area.change-status');
Route::resource('master/area', 'Master\AreaController');

Route::get('master/default-admin-fee/change-status/{id}/{status}', 'Master\DefaultAdminFeeController@changeStatus')->name('default-admin-fee.change-status');
Route::resource('master/default-admin-fee', 'Master\DefaultAdminFeeController');

Route::get('master/leasing/change-status/{id}/{status}', 'Master\LeasingController@changeStatus')->name('leasing.change-status');
Route::resource('master/leasing', 'Master\LeasingController');