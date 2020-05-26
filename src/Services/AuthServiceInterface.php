<?php

namespace Edu\Sso\Repositories;


interface AuthServiceInterface
{
    public function getRedirectResponse();

    public function loginUser($user);
}
