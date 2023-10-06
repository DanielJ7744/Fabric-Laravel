<?php

namespace App\Http\Controllers\Api;

use App\Events\PasswordUpdateFailed;
use App\Events\PasswordUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UpdateMyPasswordRequest;
use App\Models\Fabric\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class MyPasswordController extends Controller
{
    /**
     * Update the authenticated user's password.
     *
     * @param  \App\Http\Requests\Api\UpdateMyPasswordRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateMyPasswordRequest $request): JsonResponse
    {
        if (!Hash::check($request->current_password, $request->user()->password)) {
            event(new PasswordUpdateFailed($request->user()));

            abort(Response::HTTP_FORBIDDEN, 'The current password does not match.');
        }

        User::withoutEvents(function () use ($request) {
            $request->user()->password = $request->password;
            $request->user()->save();
        });

        event(new PasswordUpdated($request->user()));

        return response()->json(['message' => 'Password updated successfully.']);
    }
}
