<?php
Route::group(['middleware' => ['auth', 'can:dashboard.view']], function () {
    Route::mResource('users', 'UserController');
    Route::mResource('guards', 'GuardController', ['except' => ['show']]);
    Route::mResource('guards/{guard}/positions', 'GuardPositionController', [
        'except' => ['show', 'create', 'edit'],
        'as' => 'dashboard.guards'
        ]);
    Route::mResource('larators', 'UserController');

    Route::get(null, 'DashboardController@index')->name('dashboard');
});
