<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        try {
            // Parse the token from the Authorization header and authenticate
            $admin = JWTAuth::parseToken()->authenticate();

            if (!$admin) {
                return response()->json(['message' => 'User not found.'], 401);
            }

        } catch (TokenExpiredException $e) {
            return response()->json(['message' => 'Token expired. Please log in again.'], 401);

        } catch (TokenInvalidException $e) {
            return response()->json(['message' => 'Token invalid.'], 401);

        } catch (JWTException $e) {
            return response()->json(['message' => 'Token not provided.'], 401);
        }

        return $next($request);
    }
}