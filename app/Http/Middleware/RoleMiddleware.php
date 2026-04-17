<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
            try {
            if (!auth()->check()) {
                return response()->json(['message' => 'Non authentifie'], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Non authentifie'], 401);
        }

        if (!in_array(auth()->user()->role, $roles)) {
            return response()->json(['message' => 'Acces interdit. Role insuffisant'], 403);
        }

        return $next($request);
    }
}
