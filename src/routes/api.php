<?php
Route::group(['middleware' => ['auth:api', 'maravel']], function () {
    Route::get('me', 'UserController@me');
    Route::apiResource('users', 'UserController', ['as' => 'api']);
});
Route::post('login', 'UserController@login');
Route::post('logout', 'UserController@logout');
Route::post('register', 'UserController@register');
