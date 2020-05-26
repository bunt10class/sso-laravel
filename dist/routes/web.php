<?php

use Illuminate\Support\Facades\Route;

Route::get('/sso/' . config('sso.project') . '/redirect', 'SsoController@redirectToProvider');
Route::get('/sso/' . config('sso.project') . '/callback', 'SsoController@handleProviderCallback');
