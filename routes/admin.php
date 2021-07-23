<?php

Route::get('/auth/login', 'AdminAuth\CustomAuthController@login')->name('login');
Route::post('/auth/login', 'AdminAuth\CustomAuthController@checkLogin');

Route::get('/auth/register', 'AdminAuth\CustomAuthController@register');
Route::post('/auth/register', 'AdminAuth\CustomAuthController@checkRegister');
Route::get('/auth/logout', 'AdminAuth\CustomAuthController@logout');
