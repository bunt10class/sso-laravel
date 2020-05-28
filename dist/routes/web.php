<?php

use Illuminate\Support\Facades\Route;

Route::get('/redirect', 'SsoController@redirectToProvider');
Route::get('/callback', 'SsoController@handleProviderCallback');
