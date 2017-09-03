<?php

Route::get('setting/approval/delete/{id}', 'Setting\ApprovalController@delete')->name('approval.delete');
Route::put('setting/approval/change-level', 'Setting\ApprovalController@changeLevel')->name('approval.change-level');
Route::resource('setting/approval', 'Setting\ApprovalController');

Route::get('setting/role/change-status/{id}/{status}', 'Setting\RoleController@changeStatus')->name('role.change-status');
Route::resource('setting/role', 'Setting\RoleController');

Route::get('setting/user/change-status/{id}/{status}', 'Master\UserController@changeStatus')->name('user.change-status');
Route::resource('setting/user', 'Master\UserController');