<?php

namespace Edu\Sso\Providers;

use Edu\Sso\Interfaces\AuthServiceInterface;
use Edu\Sso\Interfaces\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class InterfacesServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        $this->app->bind(AuthServiceInterface::class, function() {
            return resolve(config('sso.classes.auth_service'));
        });
        $this->app->bind(UserRepositoryInterface::class, function() {
            return resolve(config('sso.classes.user_repository'));
        });
    }
}
