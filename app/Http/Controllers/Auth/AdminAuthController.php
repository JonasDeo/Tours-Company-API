<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    // POST /api/admin/login
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        // Attempt login via the 'admin' JWT guard
        $token = auth('admin')->attempt([
            'email'    => $request->email,
            'password' => $request->password,
        ]);

        if (!$token) {
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }

        $admin = auth('admin')->user();

        return $this->tokenResponse($token, $admin);
    }

    // POST /api/admin/refresh
    public function refresh(): JsonResponse
    {
        try {
            $token = auth('admin')->refresh();
            return $this->tokenResponse($token, auth('admin')->user());
        } catch (\Exception $e) {
            return response()->json(['message' => 'Token refresh failed.'], 401);
        }
    }

    // POST /api/admin/logout
    public function logout(): JsonResponse
    {
        auth('admin')->logout();
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
            'name'                  => 'sometimes|string|max:100',
            'email'                 => 'sometimes|email|unique:admins,email,' . $admin->id,
            'current_password'      => 'required_with:password|string',
            'password'              => 'sometimes|string|min:8|confirmed',
            'password_confirmation' => 'required_with:password|string',
        ]);

        // Verify current password before allowing a change
        if (isset($data['password'])) {
            if (!Hash::check($request->current_password, $admin->password)) {
                return response()->json([
                    'message' => 'The current password is incorrect.',
                    'errors'  => ['current_password' => ['Current password is incorrect.']],
                ], 422);
            }
            $data['password'] = bcrypt($data['password']);
        }

        unset($data['current_password'], $data['password_confirmation']);

        $admin->update($data);

        return response()->json(['message' => 'Profile updated.', 'admin' => $admin]);
    }

    // ── Helper ────────────────────────────────────────────────────────────────

    private function tokenResponse(string $token, $admin): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => config('jwt.ttl') * 60,
            'admin'        => [
                'id'    => $admin->id,
                'name'  => $admin->name,
                'email' => $admin->email,
            ],
        ]);
    }
}