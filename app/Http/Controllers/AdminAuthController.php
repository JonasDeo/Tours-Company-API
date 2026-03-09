<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminAuthController extends Controller
{
    // POST /api/admin/login
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (! $admin || ! Hash::check($request->password, $admin->password)) {
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }

        $token = JWTAuth::fromUser($admin);

        return $this->tokenResponse($token, $admin);
    }

    // POST /api/admin/refresh
    public function refresh(): JsonResponse
    {
        try {
            $token = JWTAuth::parseToken()->refresh();
            return $this->tokenResponse($token, auth('admin')->user());
        } catch (\Exception $e) {
            return response()->json(['message' => 'Token refresh failed.'], 401);
        }
    }

    // POST /api/admin/logout
    public function logout(): JsonResponse
    {
        JWTAuth::parseToken()->invalidate();
        return response()->json(['message' => 'Logged out.']);
    }

    // GET /api/admin/me
    public function me(): JsonResponse
    {
        $admin = auth('admin')->user();
        return response()->json([
            'id'    => $admin->id,
            'name'  => $admin->name,
            'email' => $admin->email,
        ]);
    }

    // PUT /api/admin/me
    public function updateMe(Request $request): JsonResponse
    {
        $admin = auth('admin')->user();

        $data = $request->validate([
            'name'             => 'sometimes|string|max:100',
            'email'            => 'sometimes|email|unique:admins,email,' . $admin->id,
            'password'         => 'sometimes|string|min:8|confirmed',
        ]);

        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $admin->update($data);

        return response()->json(['message' => 'Profile updated.', 'admin' => $admin]);
    }

    // ── Helper 

    private function tokenResponse(string $token, $admin): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => config('jwt.ttl') * 60,
            'admin' => [
                'id'    => $admin->id,
                'name'  => $admin->name,
                'email' => $admin->email,
            ],
        ]);
    }
}