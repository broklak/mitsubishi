<?php

Route::resource('order/leasing-rate', 'Spk\LeasingRateController');
Route::resource('order/insurance-rate', 'Spk\InsuranceRateController');
Route::resource('insentif/sales-bonus', 'Insentif\SalesBonusController');

Route::get('order/approve/{id}/{level}', 'Spk\OrderController@approveSpk')->name('order.approve');
Route::resource('order/simulation', 'Spk\SimulationController');
Route::resource('order', 'Spk\OrderController');