<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\UnsubscribeWebhookException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreIntegrationRequest;
use App\Http\Requests\Api\UpdateIntegrationRequest;
use App\Http\Resources\IntegrationResource;
use App\Models\Fabric\Company;
use App\Models\Fabric\Integration;
use App\Queries\IntegrationQuery;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IntegrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param IntegrationQuery $query
     *
     * @return AnonymousResourceCollection
     *
     * @throws AuthorizationException
     */
    public function index(Request $request, IntegrationQuery $query): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Integration::class);

        if ($request->to_tree) {
            $integrations = $query->get()->toTree();
        } else {
            $integrations = $query->paginate();
        }

        return IntegrationResource::collection($integrations);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreIntegrationRequest $request
     *
     * @return IntegrationResource
     *
     * @throws AuthorizationException
     */
    public function store(StoreIntegrationRequest $request): IntegrationResource
    {
        $this->authorize('create', Integration::class);

        $validated = array_merge($request->validated(), ['server' => config('fabric.integration_server')]);
        $integration = Company::current()->integrations()->create($validated);

        $integration->generateIdxTable();

        return new IntegrationResource($integration);
    }

    /**
     * Display the specified resource.
     *
     * @param Integration $integration
     * @param IntegrationQuery $query
     *
     * @return IntegrationResource
     *
     * @throws AuthorizationException
     */
    public function show(Integration $integration, IntegrationQuery $query): IntegrationResource
    {
        $this->authorize('view', $integration);

        $integration = $query
            ->whereKey($integration)
            ->firstOrFail();

        return new IntegrationResource($integration);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateIntegrationRequest $request
     * @param Integration $integration
     *
     * @return IntegrationResource
     *
     * @throws AuthorizationException
     */
    public function update(UpdateIntegrationRequest $request, Integration $integration): IntegrationResource
    {
        $this->authorize('update', $integration);

        $integration = tap($integration)->update($request->validated());

        return new IntegrationResource($integration);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Integration $integration
     *
     * @return JsonResponse
     *
     * @throws AuthorizationException
     */
    public function destroy(Integration $integration): JsonResponse
    {
        $this->authorize('delete', $integration);

        try {
            $integration->webhooks->each->delete();
        } catch (UnsubscribeWebhookException $exception) {
            abort($exception->getCode(), $exception->getMessage());
        }

        $integration->delete();

        return response()->json([
            'message' => 'Integration deleted successfully.'
        ]);
    }
}
