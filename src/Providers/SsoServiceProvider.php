<?php

namespace Edu\Sso\Providers;

use Illuminate\Support\ServiceProvider;
use Edu\Sso\Console\CreatePassportClient;
use Edu\Sso\Interfaces\AuthServiceInterface;
use Edu\Sso\Interfaces\UserRepositoryInterface;

class SsoServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../dist/config/sso.php' => config_path('sso.php'),
        ], 'config');

        $this->commands([
            CreatePassportClient::class,
        ]);
    }

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
