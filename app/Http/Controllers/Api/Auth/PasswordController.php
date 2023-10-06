<?php

namespace App\Http\Controllers\Api\Auth;

use App\Events\PasswordResetFailed;
use App\Http\Controllers\Controller;
use App\Models\Fabric\PasswordReset;
use App\Models\Fabric\User;
use Illuminate\Auth\Events\PasswordReset as PasswordWasReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    public function email(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::firstWhere('email', $request->email);

        if ($user) {
            $user->update(['remember_token' => Str::random(10)]); // TODO: investigate why/if this is necessary
            $passwordResetToken = Password::getRepository()->create($user);
            $user->sendPasswordResetNotification($passwordResetToken);
        }

        return response()->json(['message' => 'Please check your email for a password reset link.']);
    }

    public function reset(Request $request): JsonResponse
    {
        Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => $this->passwordValidationRules(),
            'password_confirmation' => 'required|same:password',
            'token' => 'required'
        ], $this->passwordValidationMessages())->validate();

        try {
            $user = User::where('email', $request->email)->firstOrFail();

            $storedUserPasswordResetToken = PasswordReset::where('email', $user->email)->first()->token;

            if (!Hash::check($request->token, $storedUserPasswordResetToken)) {
                throw new \Exception('Invalid reset token');
            }

            $user->remember_token = Str::random(10);
            $user->password = $request->input('password');
            $user->save();

            event(new PasswordWasReset($user));

            return response()->json([
                'message' => 'Password Set Successfully.',
            ]);
        } catch (\Throwable $th) {
            event(new PasswordResetFailed($user, $request->token));

            return response()->json([
                'message' => 'Error setting password. Please try with a fresh reset token.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    private function passwordValidationRules(): array
    {
        return [
            'required',
            'min:8',
            'regex:/[A-Z]|[a-z]/',
            'regex:/[0-9]/',
            'regex:/[\W_]/',
            'confirmed',
        ];
    }

    private function passwordValidationMessages(): array
    {
        return [
            'regex' => 'Your password must include letters, numbers and special characters.',
            'min' => 'Your password must be at least 8 characters long.',
        ];
    }
}
