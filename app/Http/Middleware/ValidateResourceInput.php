<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateResourceInput // this middleware is for applying the validation on the the resource attributes not to on the original attributes of the model (like 'identifier' insted of 'id' etc..)
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $modelResource): Response
    {
        $newInput = []; 

        foreach ($request->request->all() as $input => $value) { // $request->request->all() gets just the passed request parameters like title and description etc..
            $newInput[$modelResource::originalAttribute($input)] = $value; // assign the resource values like 'title' insted of the original model values like 'name', (now the 'title' will be used not the 'name')
        }

        $request->replace($newInput); // replace the old request parameters with the new newInput (resource parameters array).

        return $next($request);
    }
}
