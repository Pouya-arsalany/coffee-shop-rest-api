<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if ($user && $user->is_admin) {
                return $next($request);
            }

            return response()->json(['error' => 'Forbidden. Admin access only.'], 403);

        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Token has expired. Please log in again.'], 401);

        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token is invalid.'], 401);

        } catch (JWTException $e) {
            return response()->json(['error' => 'Token not found or malformed.'], 401);
        }
    }
}
