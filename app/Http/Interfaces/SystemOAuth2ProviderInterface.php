<?php

namespace App\Http\Interfaces;

use App\Exceptions\InvalidAccessTokenResponseException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Psr\Http\Message\ResponseInterface;

interface SystemOAuth2ProviderInterface
{
    public function redirect(Request $request): Response;

    public function getProviderRedirectUrl(): string;

    /**
     * @param string $temporaryToken
     *
     * @return ResponseInterface
     *
     * @throws GuzzleException
     */
    public function requestAccessToken(Request $request): ResponseInterface;

    /**
     * @param ResponseInterface $response
     *
     * @return array
     *
     * @throws InvalidAccessTokenResponseException
     */
    public function validate(ResponseInterface $response): array;
}
