<?php

namespace App\Http\Controllers\Api;

use App\Facades\SystemAuth;
use App\Facades\SystemWebhook;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreWebhookRequest;
use App\Http\Requests\Api\UpdateWebhookRequest;
use App\Http\Resources\WebhookResource;
use App\Models\Fabric\EventType;
use App\Models\Fabric\Webhook;
use App\Models\Tapestry\Connector;
use App\Models\Tapestry\Service;
use App\Queries\WebhookQuery;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;

class WebhookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param WebhookQuery $query
     *
     * @return AnonymousResourceCollection
     *
     * @throws AuthorizationException
     */
    public function index(WebhookQuery $query): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Webhook::class);

        $webhooks = $query->builder->paginate();

        return WebhookResource::collection($webhooks);
    }

    /**
     * Display the specified resource.
     *
     * @param Webhook $webhook
     * @param WebhookQuery $query
     *
     * @return WebhookResource
     *
     * @throws AuthorizationException
     */
    public function show(Webhook $webhook, WebhookQuery $query): WebhookResource
    {
        $this->authorize('view', $webhook);

        $model = $query->whereKey($webhook)->firstOrFail();

        return new WebhookResource($model);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreWebhookRequest $request
     * @return WebhookResource
     *
     * @throws AuthorizationException
     */
    public function store(StoreWebhookRequest $request): WebhookResource
    {
        $this->authorize('create', Webhook::class);

        $service = Service::find($request->service_id);
        $eventType = EventType::find($request->event_type_id);
        $sourceSystem = $service->getSourceSystem();
        $connector = $service->sourceConnector();
        $webhook = Webhook::make([
            'active' => 1,
            'integration_id' => $service->integration->id,
            'service_id' => $service->id,
            'event_type_id' => $eventType->id,
        ]);
        $attributes = array_merge(
            ['eventType' => $eventType->key, 'callbackUrl' => $webhook->getCallbackUrl()],
            $request->payload
        );
        $authService = SystemAuth::driver($sourceSystem->factory_name, $connector->fabricFormat());
        $webhookService = SystemWebhook::driver($sourceSystem->factory_name, $attributes, $authService);

        $webhook->remote_reference = $webhookService->subscribe();
        $webhook->saveOrFail();

        return new WebhookResource($webhook->load(['integration', 'eventType']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateWebhookRequest $request
     * @param Webhook $webhook
     *
     * @return WebhookResource
     *
     * @throws AuthorizationException
     */
    public function update(UpdateWebhookRequest $request, Webhook $webhook): WebhookResource
    {
        $this->authorize('update', $webhook);

        $webhook->update($request->validated());

        return new WebhookResource($webhook->load(['integration', 'eventType']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Webhook $webhook
     *
     * @return JsonResponse
     *
     * @throws AuthorizationException
     */
    public function destroy(Webhook $webhook): JsonResponse
    {
        $this->authorize('delete', $webhook);

        return response()->json([
            'message' => $webhook->delete() ? 'Webhook deleted successfully.' : 'Failed to delete webhook.'
        ]);
    }
}
