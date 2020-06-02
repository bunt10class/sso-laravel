<?php

return [
    'enabled' => env('SSO_ENABLED', false),
    'app_name' => env('SSO_APP_NAME', 'sso_app_name'),
    'app_redirect_endpoint' => env('SSO_APP_REDIRECT_ENDPOINT', 'http://sso_app.xx/sso/app_name/callback'),
    'uri_give_logged_user' => 'api/v1/sso/me',

    'classes' => [
        'user_repository' => 'App\\Repositories\\SsoUserRepository',
        'auth_service' => 'App\\Services\\SsoAuthService',
    ],

    'access_middleware' => [
        'class' => 'App\\Http\\Middleware\\CheckUserRole',
        'enable_to' => 'admin',
    ],
];