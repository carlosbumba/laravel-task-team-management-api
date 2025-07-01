<?php

namespace Team\Interface\Http\Middleware;

use Closure;
use F9Web\ApiResponseHelpers;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    use ApiResponseHelpers;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,  ...$roles): Response
    {
        if (!$request->user() || !$request->user()->hasAnyRole(...$roles)) {
            return $this->respondForbidden('Acesso negado');
        }

        return $next($request);
    }
}
