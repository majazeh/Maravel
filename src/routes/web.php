<?php
Route::resource('users', 'UserController', ['as' => 'dashboard', 'except' => []]);
Route::resource('guards', 'UserController', ['as' => 'dashboard']);
Route::resource('larators', 'UserController', ['as' => 'dashboard']);

Route::get(null, 'UserController@index')->name('dashboard');
// Route::get(null, 'Dashboard@index')->name('dashboard');
