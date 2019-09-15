<?php
Route::group(['middleware' => ['auth:api', 'maravel']], function () {
    Route::apiResource('users', 'UserController', ['as' => 'api']);
});
