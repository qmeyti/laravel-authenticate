<?php

namespace Qmeyti\LaravelAuth\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

/**
 * Check user access permission after login to routes has `alquth` middleware
 *
 * Class QlAuthCheckMiddleware
 * @package Qmeyti\LaravelAuth\Middleware
 */
class QlAuthCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /**
         * Check user access permission after login to routes has `alquth` middleware
         */
        \Qmeyti\LaravelAuth\Classes\Verify::access();

        return $next($request);
    }
}
