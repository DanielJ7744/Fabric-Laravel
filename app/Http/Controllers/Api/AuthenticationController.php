<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    /**
     * Handle the login process.
     *
     * @param Request  $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $token = $request->user()->createToken('API', ['access-api']);

            return response()->json([
                'message' => 'You have successfully logged in.',
                'token' => $token->accessToken,
            ]);
        }

        return response()->json(['message' => 'Incorrect email or password.'], 401);
    }

    /**
     * Handle the logout process.
     *
     * @param Request  $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->token()->delete();

        return response()->json(['message' => 'You have successfully logged out.']);
    }
}
