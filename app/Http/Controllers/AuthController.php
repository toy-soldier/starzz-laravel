<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Services\JwtService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request, JwtService $jwt): JsonResponse
    {
        $data = $request->validated();

        $user = User::where('username', $data['username'])->first();

        if ($user === null || ! Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }

        if (Hash::check(config('starzz.password_reset_sentinel'), $user->password)) {
            return response()->json([
                'message' => 'Password reset required before logging in.',
                'userId' => $user->user_id,
            ], 403);
        }

        $role = in_array($user->username, config('starzz.admins'), true) ? 'ADMIN' : 'USER';

        return response()->json([
            'token' => $jwt->generateToken($user->user_id, $user->username, $role),
        ]);
    }
}
