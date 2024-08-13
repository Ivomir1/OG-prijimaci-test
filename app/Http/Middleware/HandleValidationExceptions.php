<?

namespace App\Http\Middleware;

use Closure;
use Illuminate\Validation\ValidationException;

class HandleValidationExceptions
{
    public function handle($request, Closure $next)
    {
        try {
            return $next($request);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation error.',
                'messages' => $e->errors(),
            ], 422);
        }
    }
}
