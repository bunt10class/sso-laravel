<?php

namespace Edu\Sso\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;

class SsoEventServiceProvider extends EventServiceProvider
{
    protected $listen = [
        SocialiteWasCalled::class => [
            'SocialiteProviders\\LaravelPassport\\LaravelPassportExtendSocialite@handle',
        ],
    ];
}
