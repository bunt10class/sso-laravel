<?php

return [
    'enabled' => env('SSO_ENABLED', false),

    'app_name' => env('SSO_APP_NAME', 'sso_app_name'),
    'app_redirect_endpoint' => env('SSO_APP_REDIRECT_ENDPOINT', 'http://sso_app.xx/sso/app_name/callback'),

    'classes' => [
        'user_repository' => 'App\\Repositories\\SsoUserRepository',
        'auth_service' => 'App\\Services\\SsoAuthService',
    ],
    
    'get_logged_user_route' => [
        'uri' => env('SSO_URI_GIVE_LOGGED_USER', 'api/v1/sso/me'),
        'class' => '\\App\\Http\\Controllers\\AuthController',
        'method' => 'me'
    ],

    'access_middleware' => [
        'class' => 'App\\Http\\Middleware\\CheckUserRole',
        'enable_to' => 'admin',
    ],
];