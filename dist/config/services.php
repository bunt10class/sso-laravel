<?php

return [
    'laravelpassport' => [
        'host' => env('LRS_API_ENDPOINT', 'http://lrs.openlrs.ru'),
        'client_id' => env('LRS_OAUTH_CLIENT_ID', 0),
        'client_secret' => env('LRS_OAUTH_CLIENT_SECRET', ''),
        'redirect' => config('app.url') . '/sso/lrs/callback',
        'userinfo_uri' => 'api/v1/sso/me',
    ],
];