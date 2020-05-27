<?php

namespace Edu\Sso\Interfaces;

use Laravel\Socialite\Contracts\User;

interface UserRepositoryInterface
{
    public function findUserByEmail(string $email);

    public function createUser(User $user);
}
