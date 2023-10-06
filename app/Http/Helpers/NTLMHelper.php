<?php

namespace App\Http\Helpers;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use Robin\Ntlm\Credential\Password;
use Robin\Ntlm\Crypt\Hasher\HasherFactory;
use Robin\Ntlm\Crypt\Hasher\KeyedHasherFactory;
use Robin\Ntlm\Crypt\Random\NativeRandomByteGenerator;
use Robin\Ntlm\Encoding\MbstringEncodingConverter;
use Robin\Ntlm\Hasher\NtV1Hasher;
use Robin\Ntlm\Hasher\NtV2Hasher;
use Robin\Ntlm\Message\ChallengeMessageDecoder;
use Robin\Ntlm\Message\NegotiateMessageEncoder;
use Robin\Ntlm\Message\NtlmV2AuthenticateMessageEncoder;
use Robin\Ntlm\Message\ServerChallenge;

class NTLMHelper
{
    /**
     * @throws Exception|GuzzleException
     */
    public static function authenticate(
        string $hostname,
        string $url,
        string $domain,
        string $username,
        string $password
    ): ResponseInterface {
        $client = new Client(['verify' => false, 'http_errors' => false]);
        $encodingConverter = new MbstringEncodingConverter();
        $negotiateRequest = self::getNegotiateRequest($encodingConverter, $domain, $hostname, $url);
        $challengeResponse = $client->send($negotiateRequest);
        $serverChallenge = self::getDecodedChallenge($challengeResponse);
        if (!$serverChallenge) {
            throw new Exception('Unable to retrieve or decode server challenge during NTLM authentication.');
        }

        $authenticateRequest = self::getAuthenticateRequest(
            $encodingConverter,
            $url,
            $username,
            $domain,
            $hostname,
            $password,
            $serverChallenge
        );

        return $client->send($authenticateRequest);
    }

    protected static function getNegotiateRequest(
        MbstringEncodingConverter  $encodingConverter,
        string $domain,
        string $hostname,
        string $url
    ): Request {
        $negotiateMessageEncoder = new NegotiateMessageEncoder($encodingConverter);
        $negotiateMessage = $negotiateMessageEncoder->encode(
            $domain,
            $hostname
        );

        return new Request('get', $url, [
            'Authorization' => sprintf('NTLM %s', base64_encode($negotiateMessage))
        ]);
    }

    /**
     * @throws Exception
     */
    protected static function getDecodedChallenge(ResponseInterface $response): ?ServerChallenge
    {
        $authenticateHeaders = $response->getHeader('WWW-Authenticate');
        foreach ($authenticateHeaders as $headerString) {
            $ntlmMatches = preg_match('/NTLM( (.*))?/', $headerString, $ntlmHeader);
            if ($ntlmMatches === 1 && isset($ntlmHeader[2])) {
                return (new ChallengeMessageDecoder())->decode(base64_decode($ntlmHeader[2]));
            }
        }

        return null;
    }

    protected static function getAuthenticateRequest(
        MbstringEncodingConverter $encodingConverter,
        string $url,
        string $username,
        string $domain,
        string $hostname,
        string $password,
        ServerChallenge $serverChallenge
    ): Request {
        $keyedHasherFactory = KeyedHasherFactory::createWithDetectedSupportedAlgorithms();
        $hasherFactory = HasherFactory::createWithDetectedSupportedAlgorithms();
        $nt1Hasher = new NtV1Hasher($hasherFactory, $encodingConverter);
        $nt2Hasher = new NtV2Hasher($nt1Hasher, $keyedHasherFactory, $encodingConverter);
        $randomByteGenerator = new NativeRandomByteGenerator();
        $authenticateMessageEncoder = new NtlmV2AuthenticateMessageEncoder(
            $encodingConverter,
            $nt2Hasher,
            $randomByteGenerator,
            $keyedHasherFactory
        );

        $authenticateMessage = $authenticateMessageEncoder->encode(
            $username,
            $domain,
            $hostname,
            new Password($password),
            $serverChallenge
        );

        return new Request('get', $url, [
            'Authorization' => sprintf('NTLM %s', base64_encode($authenticateMessage))
        ]);
    }
}
