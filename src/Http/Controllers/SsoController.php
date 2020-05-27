<?php

namespace Edu\Sso\Http\Controllers;

use Edu\Sso\Interfaces\AuthServiceInterface;
use Edu\Sso\Interfaces\UserRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Laravel\Socialite\Facades\Socialite;

class SsoController extends Controller
{
    /**
     * запрос на разрешение доступа к аккаунту в стороннее приложение
     * @return RedirectResponse
     */
    public function redirectToProvider(): RedirectResponse
    {
        return Socialite::with('laravelpassport')->redirect();
    }

    /**
     * получение пользователя авторизованного в стороннем приложении,
     * создание пользователя в данном приложении с ролью админа если это необходимо,
     * и аутентификация
     * @param AuthServiceInterface $authService
     * @param UserRepositoryInterface $userRepo
     * @return RedirectResponse
     */
    public function handleProviderCallback(
        AuthServiceInterface $authService,
        UserRepositoryInterface $userRepo
    ): RedirectResponse
    {
        $redirect = $authService->getRedirectResponse();
        
        try {
            $socialiteUser = Socialite::driver('laravelpassport')->user();
        } catch (\Exception $exception) {
            return $redirect->with('error', 'Error while get user from another application');
        }

        $email = $socialiteUser->getEmail();
        if (is_null($email)) {
            return $redirect->with('error', 'User from another application doesn\'t have email');
        }

        $user = $userRepo->findUserByEmail($email);
        if (is_null($user)) {
            $user = $userRepo->createUser($socialiteUser);
        }

        $authService->loginUser($user);

        return $redirect;
    }
}
