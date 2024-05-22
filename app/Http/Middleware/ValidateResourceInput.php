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

        foreach ($request->request->all() as $input => $value) { // $request->request->all() gets only the passed request parameters like title and description etc..
            $newInput[$modelResource::originalAttribute($input)] = $value; // assign the resource values like 'title' insted of the original model values like 'name', (now the 'title' will be used not the 'name')
        }

        $request->replace($newInput); // replace the old request parameters with the new newInput (resource parameters array).

        $request->route()->setParameter('modelResource', $modelResource); // by this statement, the modelResource parameter is available now to the exception handler. This way, when the validation exception is caught, the handler can access the modelResource class to transform the error messages.
        
        // Proceed with the request
        return $next($request);
    }
}
