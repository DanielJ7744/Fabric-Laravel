<?php

namespace App\Http\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ShipmentMapHelper
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $server
     * @param string $username
     * @return array
     *
     * @throws GuzzleException
     */
    public function get(string $server, string $username): array
    {
        return $this->execute($server, $username, 'GET');
    }

    /**
     * @param string $server
     * @param string $username
     * @param array $data
     *
     * @return array
     *
     * @throws GuzzleException
     */
    public function post(string $server, string $username, array $data): array
    {
        return $this->execute($server, $username, 'POST', $data);
    }

    /**
     * @param string $server
     * @param string $username
     * @param string $method
     * @param array $data
     *
     * @return array
     *
     * @throws GuzzleException
     */
    private function execute(string $server, string $username, string $method, array $data = []): array
    {
        $url = sprintf('%s/%s/%s', ApiHelper::buildTapestryApiPath($server, 'mapper', 'tapestry.routes.core'), $username, 'Shipment');
        $options = [];

        if ($method === 'POST' || $method === 'PUT') {
            $options['json'] = $data;
        }

        return json_decode((string) $this->client->request($method, $url, $options)->getBody(), true);
    }
}
