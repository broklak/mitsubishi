<?php 

Route::get('setting/master/banner/change-status/{id}/{status}', 'Master\BannerController@changeStatus')->name('banner.change-status');
Route::resource('setting/master/banner', 'Master\BannerController');

Route::get('setting/master/news/change-status/{id}/{status}', 'Master\NewsController@changeStatus')->name('news.change-status');
Route::resource('setting/master/news', 'Master\NewsController');

Route::get('setting/master/car-category/change-status/{id}/{status}', 'Master\CarCategoryController@changeStatus')->name('car-category.change-status');
Route::resource('setting/master/car-category', 'Master\CarCategoryController');

Route::get('setting/master/car-model/change-status/{id}/{status}', 'Master\CarModelController@changeStatus')->name('car-model.change-status');
Route::resource('setting/master/car-model', 'Master\CarModelController');

Route::get('setting/master/car-type/change-status/{id}/{status}', 'Master\CarTypeController@changeStatus')->name('car-type.change-status');
Route::resource('setting/master/car-type', 'Master\CarTypeController');

Route::get('setting/master/company/change-status/{id}/{status}', 'Master\CompanyController@changeStatus')->name('company.change-status');
Route::resource('setting/master/company', 'Master\CompanyController');

Route::get('setting/master/dealer/change-status/{id}/{status}', 'Master\DealerController@changeStatus')->name('dealer.change-status');
Route::resource('setting/master/dealer', 'Master\DealerController');

Route::get('setting/master/image/', 'Master\CustomerController@image')->name('customer.image');
Route::get('setting/master/customer/change-status/{id}/{status}', 'Master\CustomerController@changeStatus')->name('customer.change-status');
Route::resource('setting/master/customer', 'Master\CustomerController');

Route::get('setting/master/bbn/change-status/{id}/{status}', 'Master\BbnController@changeStatus')->name('bbn.change-status');
Route::resource('setting/master/bbn', 'Master\BbnController');

Route::get('setting/master/credit-month/change-status/{id}/{status}', 'Master\CreditMonthController@changeStatus')->name('credit-month.change-status');
Route::resource('setting/master/credit-month', 'Master\CreditMonthController');

Route::get('setting/master/area/change-status/{id}/{status}', 'Master\AreaController@changeStatus')->name('area.change-status');
Route::resource('setting/master/area', 'Master\AreaController');

Route::get('setting/master/default-admin-fee/change-status/{id}/{status}', 'Master\DefaultAdminFeeController@changeStatus')->name('default-admin-fee.change-status');
Route::resource('setting/master/default-admin-fee', 'Master\DefaultAdminFeeController');

Route::get('setting/master/leasing/change-status/{id}/{status}', 'Master\LeasingController@changeStatus')->name('leasing.change-status');
Route::resource('setting/master/leasing', 'Master\LeasingController');