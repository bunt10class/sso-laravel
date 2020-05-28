<?php

namespace Edu\Sso\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Edu\Sso\Interfaces\AuthServiceInterface;
use Exception;

class CheckSsoRoutes
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws Exception
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
     * @throws Exception
     */
    protected function callMiddleware(Request $request, Closure $next)
    {
        $access = config('sso.access_middleware');
        $middleware = resolve($access['class']);
        $method = 'handle';
        if (!method_exists($middleware, $method)) {
            throw new Exception('Class: "' . $access['class'] . '" doesn\'t have method: "' . $method . '". Check your sso config, that class must be middleware');
        }

        return $middleware->$method($request, fn ($request) => $next($request), $access['enable_to']);
    }
}
