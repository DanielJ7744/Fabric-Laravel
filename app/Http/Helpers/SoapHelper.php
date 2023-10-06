<?php

namespace App\Http\Helpers;

class SoapHelper
{
    public static function getSoapParameters(
        int $defaultSocketTimeout = 86400,
        int $connectionTimeout = 86400,
        int $trace = 1,
        int $exceptions = 1,
        bool $verifyPeer = false,
        bool $verifyPeerName = false,
        bool $allowSelfSigned = true
    ): array {
        ini_set('default_socket_timeout', $defaultSocketTimeout);

        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => $verifyPeer,
                'verify_peer_name' => $verifyPeerName,
                'allow_self_signed' => $allowSelfSigned,
            ]
        ]);

        return [
            'stream_context' => $context,
            'trace' => $trace,
            'exceptions' => $exceptions,
            'connection_timeout' => $connectionTimeout,
        ];
    }
}
