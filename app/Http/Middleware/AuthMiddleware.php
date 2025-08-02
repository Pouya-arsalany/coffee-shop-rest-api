<?php
namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class AuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Your session is invalid or has been tampered with. Please log in again.'], 401);
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Your session has expired. Please log in to continue.'], 401);
        } catch (Exception $e) {
            return response()->json(['error' => 'You are not logged in. Please provide your credentials.'], 401);
        }

        return $next($request);
    }
}

