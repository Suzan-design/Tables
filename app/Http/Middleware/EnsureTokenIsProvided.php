<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureTokenIsProvided
{
    public function handle(Request $request, Closure $next)
    {
        $bearerToken = $request->bearerToken();

        if (!$bearerToken) {
            return response()->json(['message' => 'Token not provided'], 401);
        }

        try {
            // Set the guard to customer-api
            Auth::shouldUse('customer-api');
            // Attempt to authenticate the user using the token
            if (!Auth::check()) {
                return response()->json(['message' => 'Invalid Token'], 401);
            }

            // If you reach here, the token is valid.
            // The authenticated user is now available via $request->user()

        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid Token', 'error' => $e->getMessage()], 401);
        }

        return $next($request);
    }
}
