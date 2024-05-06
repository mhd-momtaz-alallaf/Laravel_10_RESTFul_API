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
    public function register(): void
    {
        $this->renderable(function (Exception $exception, $request) {
            if (!$request->wantsJson()) {
                return null; // Laravel handles as usual
            }
        
            // Return a JSON response with validation error messages
            if ($exception instanceof ValidationException) {
                return $this->convertValidationExceptionToResponse($exception, $request);
            }

            if ($exception instanceof ModelNotFoundException) {
                $modelName = strtolower(class_basename($exception->getModel()));
                return $this->errorResponse("Does not exists any {$modelName} with the specified identificator", 404);
            }

            if ($exception instanceof AuthenticationException) {
                return $this->unauthenticated($request, $exception);
            }

            if ($exception instanceof AuthorizationException) {
                return $this->errorResponse($exception->getMessage(), 403);
            }

            if ($exception instanceof MethodNotAllowedHttpException) {
                return $this->errorResponse('The specified method for the request is invalid', 405);
            }

            if ($exception instanceof NotFoundHttpException) {
                return $this->errorResponse('The specified URL cannot be found', 404);
            }

            if ($exception instanceof HttpException) {
                return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
            }

            if ($exception instanceof QueryException) {
                $errorCode = $exception->errorInfo[1];
                if ($errorCode == 1451) {
                    return $this->errorResponse('Cannot remove this resource permanently. It is related with an other resource', 409);
                }
            }

            if (config('app.debug')) {
                return parent::render($request, $exception);            
            }

            return $this->errorResponse('Unexpected Exception. Try later', 500); // for Any other Exception
        });
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->errorResponse('Unauthenticated.', 401);
    }
    
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();
        
        return $this->errorResponse($errors, 422);
    }
}