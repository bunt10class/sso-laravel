<?php

namespace Edu\Sso\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;

class SsoRouteServiceProvider extends RouteServiceProvider
{
    public function map()
    {
        Route::prefix('/sso/' . config('sso.application_name'))
            ->namespace('Edu\Sso\Http\Controllers')
            ->middleware(['web'])
            ->group(__DIR__ . '/../../dist/routes/web.php');
    }
}
