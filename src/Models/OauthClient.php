<?php

namespace Edu\Sso\Models;

use Laravel\Passport\Client;

class OauthClient extends Client
{
    /**
     * @return bool
     */
    public function skipsAuthorization(): bool
    {
        return true;
    }
}
