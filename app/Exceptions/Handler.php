<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpFoundation\Response;

class Handler extends ExceptionHandler
{
    // jelikož dělám API vyjímky chytám a vracím jako JSON

    public function render($request, Throwable $exception)
    {
        // když něco neprojde validací, tak to tady zachytím.vrátím JSON s chybovou hláškou a tím, co nesedí.
        if ($exception instanceof ValidationException) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $exception->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    
        // Když hledám něco v databázi a není to tam   
        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'message' => 'Resource not found',
            ], Response::HTTP_NOT_FOUND);
        }
    
        // tohle je pro případy, kdy dojde k nějaké HTTP chybě, třeba zákaz přístupu nebo něco takovýho
        if ($exception instanceof HttpException) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], $exception->getStatusCode());
        }
    
        // pokud je to nějaká jiná chyba, tak vrátím obecnou hlášku
        return response()->json([
            'message' => 'An error occurred',
            'error' => $exception->getMessage(),
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    



    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
