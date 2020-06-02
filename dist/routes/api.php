<?php

use Illuminate\Support\Facades\Route;

$routeData = config('sso.get_logged_user_route');

if (is_array($routeData)) {
    Route::get($routeData['uri'], $routeData['class'] . '@' . $routeData['method']);
}
