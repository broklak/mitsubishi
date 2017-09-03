<?php

Route::get('insentif/do/change-type/{id}/{status}', 'Insentif\DeliveryOrderController@changeType')->name('delivery-order.change-type');
Route::get('insentif/do', 'Insentif\DeliveryOrderController@index')->name('delivery-order.index');
Route::get('insentif/do/show/{id}', 'Insentif\DeliveryOrderController@show')->name('delivery-order.show');
Route::resource('insentif/fleet-rate', 'Insentif\FleetRateController');