<?php

Route::resource('order/leasing-rate', 'Spk\LeasingRateController');
Route::resource('order/insurance-rate', 'Spk\InsuranceRateController');

Route::get('order/approve/{id}/{level}', 'Spk\OrderController@approveSpk')->name('order.approve');
Route::post('order/reject', 'Spk\OrderController@rejectSpk')->name('order.reject');
Route::resource('order/simulation', 'Spk\SimulationController');
Route::resource('order', 'Spk\OrderController');