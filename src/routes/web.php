<?php
Route::group(['middleware' => ['auth']], function () {
    Route::mResource('users', 'UserController');
    Route::mResource('guards', 'UserController');
    Route::mResource('larators', 'UserController');

    Route::get(null, 'UserController@index')->name('dashboard');
});
