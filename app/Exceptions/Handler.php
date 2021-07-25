<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponser;

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

    protected function unauthenticated($request, Throwable $exception)
    {
        if ($request->expectsJson()) {
            return $this->error('Unauthenticated', 401);
        }
        return redirect()->guest('login');
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof AuthorizationException) {
            return $this->error('Не хватает прав для выполнение данного действия', 403);
        }

        return parent::render($request, $e);
    }

//    public function render($request, Throwable $exception)
//    {
//        var_dump('error in '.self::class);
//        var_dump($exception->getMessage());die;
//        return response()->json(['error' => '$exception->getMessage()']);
//    }
}
