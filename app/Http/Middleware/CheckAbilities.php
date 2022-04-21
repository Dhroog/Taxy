<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class CheckAbilities
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse
     * @throws AuthenticationException
     */
    public function handle(Request $request, Closure $next,...$abilities)
    {
        if (! $request->user() || ! $request->user()->currentAccessToken()) {
            return response()->json('Unauthenticated',401);
        }

        foreach ($abilities as $ability) {
            if (! $request->user()->tokenCan($ability)) {
                return response()->json('Access Denied',400);
            }
        }

        return $next($request);
    }
}
