<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ActiveAcount
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
        if( !auth()->user()->status )
        {
            return response()->json('Access Denied',400);
        }
        return $next($request);
    }
}
