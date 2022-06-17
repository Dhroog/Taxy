<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BannedAccount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if( $request->user()->banned )
        {
            return response()->json('Access Denied your Account is Banned',501);
        }
        return $next($request);
    }
}
