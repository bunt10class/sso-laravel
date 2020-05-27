<?php

namespace Edu\Sso\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Edu\Sso\Exceptions\SsoNotExistsMethodException;
use Edu\Sso\Interfaces\AuthServiceInterface;

class CheckSsoRoutes
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws SsoNotExistsMethodException
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->is('sso/*') and !$request->is('oauth/*')) {
            return $next($request);
        }

        if (!config('services.sso_enabled')) {
            $authService = resolve(AuthServiceInterface::class);
            return $authService->getRedirectResponse();
        }

        if ($request->is('oauth/*')) {
            return $this->callMiddleware($request, $next);
        }

        return $next($request);
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws SsoNotExistsMethodException
     */
    protected function callMiddleware(Request $request, Closure $next)
    {
        $access = config('sso.access_middleware');
        $middleware = resolve($access['class']);
        $method = 'handle';
        if (!method_exists($middleware, $method)) {
            throw new SsoNotExistsMethodException($access['class'], $method, 'check access for sso for logged in user');
        }

        return $middleware->$method($request, fn ($request) => $next($request), $access['enable_to']);
    }
}
