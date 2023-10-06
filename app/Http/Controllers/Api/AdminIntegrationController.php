<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Fabric\Company;
use Illuminate\Http\JsonResponse;
use App\Models\Fabric\Integration;
use App\Http\Controllers\Controller;
use App\Queries\AdminIntegrationQuery;
use App\Http\Resources\IntegrationResource;
use Illuminate\Auth\Access\AuthorizationException;
use App\Http\Requests\Api\AdminStoreIntegrationRequest;
use App\Http\Requests\Api\AdminUpdateIntegrationRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminIntegrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param AdminIntegrationQuery $query
     *
     * @return AnonymousResourceCollection
     *
     * @throws AuthorizationException
     */
    public function index(Request $request, AdminIntegrationQuery $query): AnonymousResourceCollection
    {
        $this->authorize('read admin-integration');

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
     * @param AdminStoreIntegrationRequest $request
     *
     * @return IntegrationResource
     *
     * @throws AuthorizationException
     */
    public function store(AdminStoreIntegrationRequest $request): IntegrationResource
    {
        $company = Company::firstWhere('id', $request->company_id);

        $validated = array_merge($request->validated(), ['server' => config('fabric.integration_server')]);
        $integration = $company->integrations()->create($validated);

        $integration->generateIdxTable();

        return new IntegrationResource($integration);
    }

    /**
     * Display the specified resource.
     *
     * @param Integration $integration
     * @param AdminIntegrationQuery $query
     *
     * @return IntegrationResource
     *
     * @throws AuthorizationException
     */
    public function show(Integration $integration, AdminIntegrationQuery $query): IntegrationResource
    {
        $this->authorize('search admin-integration');

        $integration = $query
            ->whereKey($integration)
            ->firstOrFail();

        return new IntegrationResource($integration);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AdminUpdateIntegrationRequest $request
     * @param Integration $integration
     *
     * @return IntegrationResource
     *
     * @throws AuthorizationException
     */
    public function update(AdminUpdateIntegrationRequest $request, Integration $integration): IntegrationResource
    {
        $this->authorize('update', $integration);

        $integration->update($request->validated());

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

        $integration->delete();

        return response()->json([
            'message' => 'Integration deleted successfully.'
        ]);
    }
}
