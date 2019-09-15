<?php
Route::group(['middleware' => ['auth']], function () {
    Route::resource('users', 'UserController', ['as' => 'dashboard', 'except' => []]);
    Route::resource('guards', 'UserController', ['as' => 'dashboard']);
    Route::resource('larators', 'UserController', ['as' => 'dashboard']);

    Route::get(null, 'UserController@index')->name('dashboard');
});
