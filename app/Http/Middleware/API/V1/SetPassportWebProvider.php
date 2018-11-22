<?php

namespace App\Http\Middleware\API\V1;

use Closure;
use Illuminate\Support\Facades\Config;

class SetPassportWebProvider
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Config::set('auth.guards.api.provider', 'web');
        return $next($request);
    }
}
