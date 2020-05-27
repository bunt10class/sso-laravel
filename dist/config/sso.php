<?php

$applicationName = env('SSO_NAME', 'application_name');

return [
    'application_name' => $applicationName,
    'application_host' => env('SSO_HOST', 'http://application.xx'),

    'classes' => [
        'user_repository' => 'Sso\\Repositories\\SsoUserRepository',
        'auth_service' => 'Sso\\Services\\SsoAuthService',
    ],

    'access_middleware' => [
        'class' => 'CheckUserRoleExample',
        'enable_to' => 'admin',
    ],

    'routes' => [
        'redirect' => '/sso/'. $applicationName . '/redirect',
        'callback' => '/sso/'. $applicationName . '/callback',
    ],
];