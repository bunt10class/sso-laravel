<?php

use Illuminate\Support\Facades\Route;

$routeData = config('sso.get_logged_user_route');

Route::get($routeData['uri'], $routeData['class'] . '@' . $routeData['method']);
