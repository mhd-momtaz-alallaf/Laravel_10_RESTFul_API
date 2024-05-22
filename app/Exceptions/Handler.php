<?php

namespace App\Exceptions;

use Exception;
use App\Traits\ApiResponser;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponser;
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    
    // public function register(): void
    // {
    //     $this->renderable(function (Exception $exception, $request) {
    //         if (!$request->wantsJson()) {
    //             return null; // Laravel handles as usual
    //         }
        
    //         // Return a JSON response with validation error messages
    //         if ($exception instanceof ValidationException) {
    //             return $this->convertValidationExceptionToResponse($exception, $request);
    //         }

    //         if ($exception instanceof ModelNotFoundException) {
    //             $modelName = strtolower(class_basename($exception->getModel()));
    //             return $this->errorResponse("Does not exists any {$modelName} with the specified identificator", 404);
    //         }

    //         if ($exception instanceof AuthenticationException) {
    //             return $this->unauthenticated($request, $exception);
    //         }

    //         if ($exception instanceof AuthorizationException) {
    //             return $this->errorResponse($exception->getMessage(), 403);
    //         }

    //         if ($exception instanceof MethodNotAllowedHttpException) {
    //             return $this->errorResponse('The specified method for the request is invalid', 405);
    //         }

    //         if ($exception instanceof NotFoundHttpException) {
    //             return $this->errorResponse('The specified URL cannot be found', 404);
    //         }

    //         if ($exception instanceof HttpException) {
    //             return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
    //         }

    //         if ($exception instanceof QueryException) {
    //             $errorCode = $exception->errorInfo[1];
    //             if ($errorCode == 1451) {
    //                 return $this->errorResponse('Cannot remove this resource permanently. It is related with an other resource', 409);
    //             }
    //         }

    //         if (config('app.debug')) {
    //             return parent::render($request, $exception);            
    //         }

    //         return $this->errorResponse('Unexpected Exception. Try later', 500); // for Any other Exception
    //     });
    //  }

    // protected function unauthenticated($request, AuthenticationException $exception)
    // {
    //     return $this->errorResponse('Unauthenticated.', 401);
    // }
    
    // protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    // {
    //     $errors = $e->validator->errors()->getMessages();
        
    //     return $this->errorResponse($errors, 422);
    // }

    protected function invalidJson($request, ValidationException $exception)
    {
        // Ensure we have a valid $modelResource class
        if ($request->route() && $request->route()->parameter('modelResource')) {
            $modelResource = $request->route()->parameter('modelResource');
        } else {
            return parent::invalidJson($request, $exception);
        }

        //this is an example of the response collection:
        // { 
        //     "message": "The name field is required. (and 1 more error)",
        //     "errors": {
        //         "name": [
        //             "The name field is required."
        //         ],
        //         "description": [
        //             "The description field is required."
        //         ]
        //     }
        // }

        // Transform each error field name and message
        $errors = $exception->errors();
        $transformedErrors = [];

        foreach ($errors as $field => $errorMessages) { // the "errors" key have by example the key='name' and its $errorMessages[0]="The name field is required", we want to change the 'name'key to be 'title' , the next for loop is to change the 'name' attribute in the ($errorMessages[0]="The name field is required") to be "The title field is required"
            $transformedField = $modelResource::resourceAttribute($field);
            foreach ($errorMessages as $error) {
                $transformedErrors[$transformedField][] = str_replace($field, $transformedField, $error);
            }
        }

        // Transform the main message
        $originalMessage = $exception->getMessage();
        $transformedMessage = $originalMessage;

        foreach (array_keys($errors) as $field) { // the same approach here but now is for the "message" key that is above the "errors" key of the response collection
            $transformedField = $modelResource::resourceAttribute($field);
            $transformedMessage = str_replace($field, $transformedField, $transformedMessage);
        }

        //this is an example of the resault response:
        // {
        //     "message": "The title field is required. (and 1 more error)",
        //     "errors": {
        //         "title": [
        //             "The title field is required."
        //         ],
        //         "details": [
        //             "The details field is required."
        //         ]
        //     }
        // }

        return response()->json([
            'message' => $transformedMessage,
            'errors' => $transformedErrors,
        ], $exception->status);
    }
}