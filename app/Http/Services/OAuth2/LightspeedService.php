<?php

namespace App\Http\Services\OAuth2;

use App\Http\Abstracts\SystemOAuth2Abstract;
use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;

class LightspeedService extends SystemOAuth2Abstract
{
    /**
     * @param Request $request
     *
     * @return ResponseInterface
     */
    public function requestAccessToken(Request $request): ResponseInterface
    {
        $request->validate(['code' => ['string', 'required']]);

        return $this->client->post(config('external-app.authentication_endpoints.lightspeed'), [
            'json' => [
                'client_id' => config('system-oauth-2.lightspeed.client_id'),
                'client_secret' => config('system-oauth-2.lightspeed.client_secret'),
                'code' => $request->code,
                'grant_type' => 'authorization_code',
            ]
        ]);
    }
}
