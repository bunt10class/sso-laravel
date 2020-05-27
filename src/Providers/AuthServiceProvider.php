<?php

namespace Edu\Sso\Providers;

use Edu\Sso\Models\OauthClient;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        Passport::routes();
        Passport::useClientModel(OauthClient::class);
    }
}
