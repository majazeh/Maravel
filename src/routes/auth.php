<?php

Route::get('/login', 'LoginController@showLoginForm')->name('login');
Route::post('/login', 'LoginController@login');
Route::post('/logout', 'LoginController@logout')->name('logout');

Route::get('/register', 'LoginController@showRegistrationForm')->name('register');

Route::get('verify/{token}', 'LoginController@emailVerify')->name('emailVerify');
Route::get('/login/google', 'LoginController@redirectToProvider');
Route::get('/oauth2callback', 'LoginController@handleProviderCallback');
Route::get('/login/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
Route::post('/login/{token}', 'ResetPasswordController@iReset')->name('password.reset');
