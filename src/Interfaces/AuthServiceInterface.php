<?php

namespace Edu\Sso\Interfaces;

use Illuminate\Http\RedirectResponse;

interface AuthServiceInterface
{
    public function getRedirectResponse(): RedirectResponse;

    public function loginUser($user): void;
}
