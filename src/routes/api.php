<?php
Route::group(['middleware' => ['auth:api']], function () {
    Route::get('me', 'UserController@me');
    Route::apiResource('users', 'UserController', ['as' => 'api']);
    Route::apiResource('guards', 'GuardController', ['as' => 'api']);
    Route::apiResource('guards/{guard}/positions', 'GuardPositionController', [
        'except' => ['show'],
        'as' => 'api.guards'
    ]);
    Route::apiResource('attachments', 'AttachmentController', ['as' => 'api']);

});
Route::post('login', 'UserController@login');
Route::post('logout', 'UserController@logout');
Route::post('register', 'UserController@register');
