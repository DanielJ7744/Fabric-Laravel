<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreConnectorOAuthClientRequest;
use App\Http\Resources\OauthClientResource;
use App\Models\Fabric\OauthClient;
use App\Models\Tapestry\Connector;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Passport\ClientRepository;

class ConnectorOauthClientController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param ClientRepository $clients
     *
     * @return void
     */
    public function __construct(ClientRepository $clients)
    {
        $this->clients = $clients;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Connector  $connector
     * @return AnonymousResourceCollection
     */
    public function index(Connector $connector): AnonymousResourceCollection
    {
        $this->authorize('viewAny', OauthClient::class);

        $clients = $connector->clients;

        return OauthClientResource::collection($clients);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreConnectorOAuthClientRequest  $request
     * @param Connector  $connector
     * @return OauthClientResource
     */
    public function store(StoreConnectorOAuthClientRequest $request, Connector $connector): OauthClientResource
    {
        $this->authorize('create', [OauthClient::class, $connector]);

        $client = $this->clients->create(
            auth()->id(),
            $request->name,
            config('app.url')
        );

        $connector->clients()->attach($client, [
            'safe_secret' => substr($client->secret, -4)
        ]);

        return new OauthClientResource($client, $client->secret);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Connector  $connector
     * @param OauthClient  $oauthClient
     * @return JsonResponse
     */
    public function destroy(Connector $connector, OauthClient $oauthClient): JsonResponse
    {
        $this->authorize('delete', [$oauthClient, $connector]);

        Validator::make(
            request()->only('confirmation'),
            ['confirmation' => ['required', Rule::in([$oauthClient->name])]],
            ['in' => 'The :attribute does not match.']
        )->validate();

        $oauthClient->delete();

        return response()->json([
            'message' => 'Client deleted successfully.'
        ]);
    }
}
