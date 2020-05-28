<?php

return [
    'enabled' => env('SSO_ENABLED', false),

    'application_name' => env('SSO_APPLICATION_NAME', 'application_name'),
    'application_redirect_endpoint' => env('SSO_APPLICATION_REDIRECT_ENDPOINT', 'http://application.xx/sso/callback'),

    'classes' => [
        'user_repository' => 'Sso\\Repositories\\SsoUserRepository',
        'auth_service' => 'Sso\\Services\\SsoAuthService',
    ],

    'access_middleware' => [
        'class' => 'CheckUserRoleExample',
        'enable_to' => 'admin',
    ],
];