<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Fabric\SocialAccount;
use App\Models\Fabric\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class SocialiteController extends Controller
{
    /**
     * Redirect the user to the authentication page for the social provider
     *
     * @param string  $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToProvider($provider): RedirectResponse
    {
        if (!in_array($provider, SocialAccount::$providers)) {
            return $this->redirectToDashboard([
                'code' => 400,
                'title' => 'Provider not supported',
                'message' => 'We do not support this provider at the moment'
            ]);
        }

        try {
            return Socialite::driver($provider)->stateless()->redirect();
        } catch (\Throwable $th) {
            return $this->redirectToDashboard(['code' => 500]);
        }
    }

    /**
     * Retrieve the user account from the social provider.
     *
     * @param string  $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback($provider): RedirectResponse
    {
        try {
            $account = Socialite::driver($provider)->stateless()->user();

            $this->ensureAccountIsVerified($account);

            $user = User::whereEmail($account->getEmail())->first();

            if (!$user) {
                Cache::put($key = Str::random(24), encrypt(compact('provider', 'account')), now()->addMinutes(20));

                return $this->redirectToDashboard([
                    'name' => $account->getName(),
                    'email' => $account->getEmail(),
                    'avatar_url' => $account->getAvatar(),
                    'social_token' => $key,
                ], '/register');
            }

            $user->addSocialAccount($provider, $account);

            $token = $user->createToken('API', ['access-api']);

            return $this->redirectToDashboard(['token' => $token->accessToken]);
        } catch (\Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return $this->redirectToDashboard([
                    'code' => 404,
                    'title' => 'No account found',
                    'message' => 'We cant find an existing account for you. Please contact your service administrator to receive an invitation'
                ]);
            } elseif ($e instanceof UnprocessableEntityHttpException) {
                return $this->redirectToDashboard([
                    'code' => 422,
                    'title' => 'Unverified email address',
                    'message' => 'Please verify your email with provider and try again'
                ]);
            }

            return $this->redirectToDashboard(['code' => 500]);
        }
    }

    /**
     * Redirect the request to the dashboard
     *
     * @param array  $parameters
     * @param string  $uri
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectToDashboard($parameters = [], $uri = '/sso/callback'): RedirectResponse
    {
        $dashboardUrl = config('dashboard.url') . $uri . '?' . http_build_query($parameters);

        return redirect()->away($dashboardUrl);
    }

    /**
     * Ensure the user's social account is verified.
     *
     * @return void
     * @throws \Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException
     */
    protected function ensureAccountIsVerified($account): void
    {
        $verified = !empty($account->user['email_verified']) and $account->user['email_verified'] === true;

        throw_if(!$verified, UnprocessableEntityHttpException::class);
    }
}
