<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SingatureMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    // this is an after middleware to modify the header name for all requests.
    public function handle($request, Closure $next, $headerName = 'X-Name'): Response // the best practice for costum Header name to be X-costumName like ('X-Name').
    {
        $response = $next($request);

        $response->headers->set($headerName, config('app.name'));

        return $response;
    }
}
