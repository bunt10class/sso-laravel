<?php

use Illuminate\Support\Facades\Route;

Route::get(config('sso.routes.redirect'), 'Edu\Sso\Http\Controllers\SsoController@redirectToProvider');
Route::get(config('sso.routes.callback'), 'Edu\Sso\Http\Controllers\SsoController@handleProviderCallback');
