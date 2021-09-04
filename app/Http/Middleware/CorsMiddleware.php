<?php

namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
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
        $origin = $request->headers->get('origin');
        $allowed = explode(',', env('ALLOWED_ORIGIN', ''));

        $currentOrigin = '*';

        if (in_array($origin, $allowed, true)) {
            $currentOrigin = $origin;
        }

        $headers = [
            'Access-Control-Allow-Origin'      => $currentOrigin,
            'Access-Control-Allow-Methods'     => 'POST, GET, OPTIONS, PUT, DELETE',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Max-Age'           => '86400',
            'Access-Control-Allow-Headers'     => 'Content-Type, Authorization, X-Requested-With, X-XSRF-Token, Origin',
        ];

        if ($request->isMethod('OPTIONS')) {
            return response()->json('{"method":"OPTIONS"}', 200, $headers);
        }

        $response = $next($request);

        foreach ($headers as $key => $value) {
            if (get_class($response) === 'Illuminate\Http\Request') {
                $response->header($key, $value);
            } else {
                $response->headers->set($key, $value);
            }
        }


        return $response;
    }
}
