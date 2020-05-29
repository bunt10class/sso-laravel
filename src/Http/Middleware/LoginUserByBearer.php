<?php

namespace Edu\Sso\Http\Middleware;

use Closure;
use Edu\Sso\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Token;
use Laravel\Passport\TokenRepository;

class LoginUserByBearer
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->is(config('sso.get_logged_user_route.uri'))) {
            /** @var Token $token */
            $token = $this->getOauthAccessToken($request->header('Authorization'));
            if (is_null($token)) {
                return $next($request);
            }
            $userId = $token->user_id;

            /** @var UserRepositoryInterface $userRepo */
            $userRepo = resolve(UserRepositoryInterface::class);
            $user = $userRepo->findUserById($userId);

            if (is_null($user)) {
                return $next($request);
            }

            Auth::loginUsingId($userId);
        }

        return $next($request);
    }

    /**
     * поиск объекта OauthAccessToken по раскодированному токену переданному в header
     * @param string|null $authorization
     * @return Token|null
     */
    protected function getOauthAccessToken(?string $authorization): ?Token
    {
        if (is_null($authorization)) {
            return null;
        }

        if (!preg_match('~^Bearer [^ ]*$~', $authorization)) {
            return null;
        }

        $jwt = trim(substr($authorization, 7));
        $jwtArray = explode('.', $jwt);
        if ((count($jwtArray)) != 3) {
            return null;
        }

        list($header, $claims, $signature) = $jwtArray;
        unset($header, $signature);
        $claims = json_decode(base64_decode($claims));

        /** @var TokenRepository $tokenRepo */
        $tokenRepo = app(TokenRepository::class);
        return $tokenRepo->find($claims->jti);
    }
}
