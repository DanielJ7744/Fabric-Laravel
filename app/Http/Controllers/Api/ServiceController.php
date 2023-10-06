<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\UnsubscribeWebhookException;
use App\Http\Controllers\Controller;
use App\Http\Helpers\IntegrationHelper;
use App\Http\Helpers\ServiceHelper;
use App\Http\Requests\Api\UpdateServiceRequest;
use App\Http\Resources\Tapestry\ServiceResource;
use App\Models\Tapestry\Service;
use App\Queries\ServiceQuery;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ServiceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param ServiceHelper $serviceHelper
     * @param IntegrationHelper $integrationHelper
     *
     * @return void
     */
    public function __construct(ServiceHelper $serviceHelper, IntegrationHelper $integrationHelper)
    {
        $this->serviceHelper = $serviceHelper;
        $this->integrationHelper = $integrationHelper;
    }

    /**
     * @param ServiceQuery $serviceQuery
     *
     * @return AnonymousResourceCollection
     *
     * @throws AuthorizationException
     */
    public function index(ServiceQuery $serviceQuery): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Service::class);

        $services = $serviceQuery->paginate(request()->perPage);

        return ServiceResource::collection($services);
    }

    /**
     * Display the specified resource.
     *
     * @param Service $service
     * @param ServiceQuery $serviceQuery
     *
     * @return ServiceResource
     *
     * @throws AuthorizationException
     */
    public function show(Service $service, ServiceQuery $serviceQuery): ServiceResource
    {
        $this->authorize('view', $service);

        $service = $serviceQuery
            ->whereKey($service)
            ->firstOrFail();

        return new ServiceResource($service);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateServiceRequest $request
     * @param Service $service
     *
     * @return ServiceResource
     *
     * @throws AuthorizationException
     */
    public function update(UpdateServiceRequest $request, Service $service): ServiceResource
    {
        $this->authorize('update', $service);

        $attributes = $request->validated();

        $attributes['from_options'] = $service->mergeFromOptions($service->from_options, $request->from_options);

        if (isset($attributes['from_options']['filters'])) {
            $attributes['from_options']['filters'] = $service->formatFilters(
                $attributes['from_options']['filters'],
                $service->getSourceFactorySystem()
            );
        }

        $service->update($attributes);

        return new ServiceResource($service);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Service $service
     *
     * @return JsonResponse
     *
     * @throws AuthorizationException
     */
    public function destroy(Service $service): JsonResponse
    {
        $this->authorize('delete', $service);

        try {
            $service->webhooks->each->delete();
        } catch (UnsubscribeWebhookException $exception) {
            abort($exception->getCode(), $exception->getMessage());
        }

        $service->update(['status' => 0, 'schedule' => 'off']);
        $service->delete();

        return response()->json([
            'message' => 'Service deleted successfully.'
        ]);
    }
}
