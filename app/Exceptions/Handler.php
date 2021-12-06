<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    // protected function prepareException(Throwable $e)
    // {
    //     if ($e instanceof ModelNotFoundException) {
    //         $e = new NotFoundHttpException($e->getMessage(), $e);
    //     } elseif ($e instanceof AuthorizationException) {
    //         $e = new AccessDeniedHttpException($e->getMessage(), $e);
    //     } elseif ($e instanceof TokenMismatchException) {
    //         return redirect()->route('login');
    //     }
    //     return $e;
    // }

    public function render($request, Throwable $e)
    {
        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }

        if ($e instanceof TokenMismatchException) {

            return redirect(route('login'))->with('message', 'You page session expired. Please try again');
        }

        return parent::render($request, $e);
    }
}
