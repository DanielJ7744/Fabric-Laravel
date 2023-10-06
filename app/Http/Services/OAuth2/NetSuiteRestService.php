<?php

namespace App\Http\Services\OAuth2;

use App\Http\Abstracts\SystemOAuth2Abstract;
use App\Http\Interfaces\SystemOAuth2StateInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;

class NetSuiteRestService extends SystemOAuth2Abstract implements SystemOAuth2StateInterface
{
    private const UNIQUE_ACC_ID_CACHE_KEY = 'oauth-2-ns-acc-id:%s';

    public function redirect(Request $request): Response
    {
        $request->validate(['credentials.account_id' => ['string', 'required', 'alpha_num']]);
        $this->setAccountId($request->credentials['account_id']);
        $this->setState();

        return parent::redirect($request);
    }

    public function getProviderRedirectUrl(): string
    {
        return sprintf(
            $this->redirectUrl,
            $this->getAccountId(),
            $this->clientId,
            Config::get('system-oauth-2.netsuite.callback_url'),
            Config::get('system-oauth-2.netsuite.scope'),
            $this->getState()
        );
    }

    private function getAccountId(): string
    {
        return Cache::get(sprintf(self::UNIQUE_ACC_ID_CACHE_KEY, Auth::user()->id));
    }

    private function setAccountId(string $accountId): void
    {
        Cache::put(
            sprintf(self::UNIQUE_ACC_ID_CACHE_KEY, Auth::user()->id),
            $accountId,
            now()->addMinutes(2)
        );
    }

    public function getState(): string
    {
        return Cache::get(sprintf(self::UNIQUE_STATE_CACHE_KEY, Auth::user()->id));
    }

    public function setState(): void
    {
        Cache::put(
            sprintf(self::UNIQUE_STATE_CACHE_KEY, Auth::user()->id),
            Str::random(1024),
            now()->addMinutes(2)
        );
    }

    /**
     * @param Request $request
     *
     * @return ResponseInterface
     */
    public function requestAccessToken(Request $request): ResponseInterface
    {
        $request->validate(['code' => ['string', 'required']]);

        return $this->client->post(Config::get('system-oauth-2.netsuite.access_token_url'), [
            'auth' => [$this->clientId, $this->clientSecret],
            'form_params' => [
                'code' => $request->code,
                'redirect_uri' => Config::get('system-oauth-2.netsuite.callback_url'),
                'grant_type' => 'authorization_code'
            ]
        ]);
    }

    public function validate(ResponseInterface $response): array
    {
        return array_merge(parent::validate($response), ['account_id' => $this->getAccountId()]);
    }
}
