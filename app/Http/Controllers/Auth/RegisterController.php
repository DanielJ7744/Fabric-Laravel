<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Fabric\Company;
use App\Models\Fabric\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /**
     * Register a new user and company with the application.
     *
     * @param \App\Http\Requests\Auth\RegisterRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $company = Company::create($request->validated()['company']);

            $company->subscriptions()->attach(Subscription::whereName('Sandbox')->firstOrFail());

            $integration = $company->integrations()->create([
                'name' => $company->name,
                'username' => Str::slug($company->name, '_'),
                'server' => config('fabric.integration_server')
            ]);

            $user = $company
                ->users()
                ->create($request->validated()['user'])
                ->assignRole('client admin');

            $integration->users()->attach($user);

            if ($socialToken = Cache::get($request->social_token)) {
                $account = decrypt($socialToken);

                $user->addSocialAccount($account['provider'], $account['account']);
            }

            $integration->generateIdxTable();

            DB::commit();

            return response()->json([
                'message' => 'Account created successfully, please login',
                'token' => $user->createToken('API', ['access-api'])->accessToken
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

            Log::emergency('Failed to create account', [
                'message' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'An error occurred while creating your account, please try again',
                'error' => $th->getMessage(),
            ], 500);
        }
    }
}
