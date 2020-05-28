<?php

namespace Edu\Sso\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider;
use Laravel\Passport\Passport;
use Edu\Sso\Models\OauthClient;

class SsoAuthServiceProvider extends AuthServiceProvider
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
