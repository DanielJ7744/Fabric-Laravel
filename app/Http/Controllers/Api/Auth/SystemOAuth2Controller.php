<?php

namespace App\Http\Controllers\Api\Auth;

use App\Exceptions\InvalidAccessTokenResponseException;
use App\Facades\SystemOAuth2;
use App\Http\Abstracts\SystemOAuth2Abstract;
use App\Http\Controllers\Controller;
use App\Http\Interfaces\SystemOAuth2StateInterface;
use App\Models\Fabric\Integration;
use App\Models\Fabric\System;
use App\Models\Tapestry\Connector;
use App\Rules\SystemHasAuthType;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SystemOAuth2Controller extends Controller
{
    protected CONST UNIQUE_CACHE_KEY = 'oauth-2-user:%s';

    /**
     * Redirect the user to the authentication page for the oauth 2 provider
     *
     * @param Request $request
     *
     * @return Response
     */
    public function redirectToProvider(Request $request): Response
    {
        $validated = $request->validate([
            'environment'        => ['required', 'string'],
            'timeZone'           => ['required', 'string'],
            'dateFormat'         => ['required', 'string'],
            'connectorName'      => ['required', 'string'],
            'authorisation_type' => ['required', 'string'],
            'system_id'          => ['required', 'exists:systems,id', new SystemHasAuthType('oauth2')],
            'integration_id'     => ['required', 'exists:integrations,id'],
        ]);

        Cache::put(
            sprintf(self::UNIQUE_CACHE_KEY, Auth::user()->id),
            $validated,
            now()->addMinutes(2)
        );

        return SystemOAuth2::driver(System::find($request->system_id)->factory_name)->redirect($request);
    }

    /**
     * Retrieve the user account from the social provider.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function handleProviderCallback(Request $request): Response
    {
        $cache = Cache::get(sprintf(self::UNIQUE_CACHE_KEY, Auth::user()->id));
        $system = System::find($cache['system_id']);

        try {
            $driver = SystemOAuth2::driver($system->factory_name);
            $this->handleState($driver, $request->state ?? null);
            $this->saveConnector($cache, $system, $driver->validate($driver->requestAccessToken($request)));

            return response('', 200)->send();
        } catch (GuzzleException|InvalidAccessTokenResponseException $exception) {
            return $this->handleError($exception);
        }
    }

    protected function handleState(SystemOAuth2Abstract $driver, ?string $state): void
    {
        if (Arr::has(class_implements($driver), SystemOAuth2StateInterface::class)) {
            abort_if($driver->getState() !== $state, 400, 'OAuth2 state mismatch.');
        }
    }

    protected function saveConnector(array $cache, System $system, array $credentials): void
    {
        $integration = Integration::find($cache['integration_id']);
        $connector = (new Connector())->setIdxTable($integration->username);
        $connector->withTrashed()->updateOrCreate(
            [
                'type' => Connector::TYPE,
                'system_chain' => $system->factory_name,
                'common_ref' => $cache['environment'],
            ],
            [
                'system_chain' => $system->factory_name,
                'common_ref' => $cache['environment'],
                'extra' =>  array_merge($credentials, [
                    'connector_name' => $cache['connectorName'],
                    'timezone' => $cache['timeZone'],
                    'date_format' => $cache['dateFormat'],
                    'authorisation_type' => $cache['authorisation_type']
                ]),
                'deleted_at' => null
            ]
        );
    }

    protected function handleError(Exception $exception): Response
    {
        switch (typeOf($exception)) {
            case GuzzleException::class:
                $message = 'Failed to retrieve access token';
                break;
            case InvalidAccessTokenResponseException::class:
                $message = 'Failed to validate access token response';
                break;
            default:
                $message = 'Failure during OAuth process';
                break;
        }

        return response(['message' => sprintf('%s: %s', $message, $exception->getMessage())], 500)->send();
    }
}
