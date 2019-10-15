<?php
Route::group(['middleware' => ['auth']], function () {
    Route::mResource('users', 'UserController');
    Route::mResource('guards', 'GuardController', ['except' => ['show']]);
    Route::mResource('guards/{guard}/positions', 'GuardPositionController', [
        'except' => ['show', 'create', 'edit'],
        'as' => 'dashboard.guards'
        ]);
    Route::mResource('larators', 'UserController');

    Route::get(null, 'DashboardController@index')->name('dashboard');
});

Route::get('test-design', function(){
    return View('layouts.app', [
        'global' => (object) [
            'title' => 'test'
        ],
        'module' => (object) [
            'header' => 'test'
        ]
    ]);
});
