<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;

class SwaggerDocsMiddleware
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
        $env = Config::get('app.env');
        if (in_array($env, ['local', 'development', 'staging'])) {
            return $next($request);
        }

        abort(404);
    }
}
