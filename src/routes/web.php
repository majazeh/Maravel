<?php
Route::resource('users', 'UserController', ['as' => 'dashboard']);
Route::resource('guards', 'UserController', ['as' => 'dashboard']);
Route::resource('larators', 'UserController', ['as' => 'dashboard']);

Route::get(null, 'Dashboard@index')->name('dashboard');
