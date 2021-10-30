<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Login.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);
        if (!auth()->attempt($credentials)) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $token = $user->createToken(env('SANCTUM_TOKEN_NAME'))->plainTextToken;

        return $this->respondWithToken($token);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return JsonResponse
     */
    private function respondWithToken($token)
    {
        return response()->json([
            'message' => 'Login',
            'data' => [
                'user' => auth()->user(),
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]
        ]);
    }
}
