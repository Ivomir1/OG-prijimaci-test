<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class HandleExceptions
{
    public function handle(Request $request, Closure $next)
    {
        try {
            return $next($request);
        } catch (ValidationException $e) {      // Chytá chyby validace a vrací JSON odpověď pokud už ji nechytil Handler vyjímek
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (ModelNotFoundException $e) {    // Chytá chybu, když není nalezen záznam v databázipokud už ji nechytil Handler vyjímek
            return response()->json([
                'message' => 'Resource not found',
            ], Response::HTTP_NOT_FOUND);
        } catch (HttpException $e) {      // chytá ostatní HTTP chybypokud už ji nechytil Handler vyjímek
            return response()->json([
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        } catch (\Exception $e) { // chytá ostatní výjimky a vrací obecnou chybovou odpověď pokud už ji nechytil Handler vyjímek      
            return response()->json([
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
