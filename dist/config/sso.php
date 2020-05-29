<?php

return [
    'enabled' => env('SSO_ENABLED', false),

    'application_name' => env('SSO_APPLICATION_NAME', 'application_name'),
    'application_redirect_endpoint' => env('SSO_APPLICATION_REDIRECT_ENDPOINT', 'http://application.xx/sso/callback'),

    'classes' => [
        'user_repository' => 'UserRepository',
        'auth_service' => 'AuthService',
    ],
    
    'get_logged_user_route' => [
        'uri' => env('SSO_URI_GIVE_LOGGED_USER', 'api/me'),
        'class' => 'AuthController',
        'method' => 'me'
    ],

    'access_middleware' => [
        'class' => 'CheckUserRoleMiddleware',
        'enable_to' => 'admin',
    ],
];