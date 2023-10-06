<?php

namespace App\Http\Controllers\Api;

use App\Facades\SystemAuth;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\Fabric\System;
use Illuminate\Http\JsonResponse;
use App\Models\Tapestry\Connector;
use App\Models\Fabric\Integration;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreConnectorRequest;
use App\Http\Requests\Api\UpdateConnectorRequest;
use App\Http\Resources\Tapestry\ConnectorResource;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ConnectorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     *
     * @throws AuthorizationException
     */
    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Connector::class);

        $connectors = Integration::all()
            ->flatMap(fn($integration) => (new Connector())
                ->setIdxTable($integration->username)
                ->get());

        return ConnectorResource::collection($connectors);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreConnectorRequest $request
     * @return ConnectorResource
     *
     * @throws AuthorizationException
     */
    public function store(StoreConnectorRequest $request): ConnectorResource
    {
        $this->authorize('create', Connector::class);
        $validated = $request->validated();
        $system = System::findOrFail($validated['system_id']);
        $credentials = Connector::mergeAdditionalCredentialFields($validated['credentials'], $validated);

        try {
            $authService = SystemAuth::driver($system->factory_name, $credentials);
            abort_if(!$authService->verify($authService->authenticate()), 422, 'Invalid credentials');
        } catch (Exception $exception) {
            abort($exception->getCode(), $exception->getMessage());
        }

        $integration = Integration::findOrFail($validated['integration_id']);
        $connector = Connector::make([
            'system_chain' => $system->factory_name,
            'extra' => $credentials
        ]);
        $connector = $connector->setIdxTable($integration->username);
        $connector = $connector->withTrashed()->updateOrCreate(
            [
                'type' => Connector::TYPE,
                'system_chain' => $system->factory_name,
                'common_ref' => $validated['environment'],
            ],
            [
                'system_chain' => $system->factory_name,
                'common_ref' => $validated['environment'],
                'extra' => $connector->tapestryFormat(),
                'deleted_at' => null
            ]
        );

        return new ConnectorResource($connector);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param Connector $connector
     *
     * @return ConnectorResource
     *
     * @throws AuthorizationException
     */
    public function show(Request $request, Connector $connector): ConnectorResource
    {
        $this->authorize('view', $connector);

        return new ConnectorResource($connector);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateConnectorRequest $request
     * @param Connector $connector
     * @return ConnectorResource
     *
     * @throws AuthorizationException
     */
    public function update(UpdateConnectorRequest $request, Connector $connector): ConnectorResource
    {
        $this->authorize('update', $connector);

        $validated = $request->validated();
        $credentials = $this->fillObfuscatedFields(Arr::get($validated, 'credentials', []), $connector);

         try {
            $system = System::where('factory_name', $connector->system_chain)->firstOrFail();
            $authService = SystemAuth::driver($system->factory_name, $credentials);
            abort_if(!$authService->verify($authService->authenticate()), 422, 'Invalid credentials');
        } catch (Exception $exception) {
            abort($exception->getCode(), $exception->getMessage());
        }

        $connector->update([
            'common_ref' => $validated['environment'],
            'extra' => Connector::mergeAdditionalCredentialFields(
                $authService::getTapestryFormat($credentials),
                $validated
            ),
        ]);

        return new ConnectorResource($connector);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Connector  $connector
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Request $request, Connector $connector): JsonResponse
    {
        $this->authorize('delete', $connector);

        $deleted = $connector->delete();

        return response()->json([
            'status' => $deleted ? 200 : 500,
            'message' => $deleted ? 'Connector deleted successfully.' : 'Failed to delete connector.'
        ]);
    }

    /**
     * Fetch any existing credentials and use those to populate missing data as front-end is obfuscated
     *
     * @param array $credentials
     * @param Connector $connector
     *
     * @return array
     */
    protected function fillObfuscatedFields(array $credentials, Connector $connector): array
    {
        $existingCredentials = $connector->fabricFormat();
        $passedCredentials = $credentials;

        return array_merge($existingCredentials, $passedCredentials);
    }
}
