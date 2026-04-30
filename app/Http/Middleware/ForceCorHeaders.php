<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForceCorsHeaders
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->isMethod('OPTIONS')) {
            return response()->noContent()
                ->header('Access-Control-Allow-Origin', $request->header('Origin') ?? '*')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, Accept')
                ->header('Access-Control-Max-Age', '86400');
        }

        $response = $next($request);

        $response->headers->set('Access-Control-Allow-Origin', $request->header('Origin') ?? '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, Accept');

        return $response;
    }
}