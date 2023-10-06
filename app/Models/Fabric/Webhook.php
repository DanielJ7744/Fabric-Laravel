<?php

namespace App\Models\Fabric;

use App\Exceptions\UnsubscribeWebhookException;
use App\Facades\SystemAuth;
use App\Facades\SystemWebhook;
use App\Models\Tapestry\Connector;
use Exception;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as IsAuditable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Tapestry\Service as ServiceAlias;

class Webhook extends FabricModel implements Auditable
{
    use IsAuditable;

    protected CONST CALLBACK_URL_FORMAT = '%s/%s/post/%s/%s/%s/%s/%s';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'active',
        'integration_id',
        'service_id',
        'remote_reference',
        'event_type_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'active'  => 'boolean',
    ];

    /**
     * Get the integration for the webhook.
     *
     * @return BelongsTo
     */
    public function integration(): BelongsTo
    {
        return $this->belongsTo(Integration::class);
    }

    /**
     * Get the service for the webhook.
     *
     * @return BelongsTo
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(ServiceAlias::class);
    }

    /**
     * Get the system event type for the webhook.
     *
     * @return BelongsTo
     */
    public function eventType(): BelongsTo
    {
        return $this->belongsTo(EventType::class);
    }

    public function getCallbackUrl(): string {
        return sprintf(
            self::CALLBACK_URL_FORMAT,
            config('webhook.host'),
            $this->service->getSourceSystem()->factory_name,
            'webhook',
            $this->service->integration->username,
            str_replace('/', '_', $this->eventType->key),
            $this->service->getDestinationSystem()->factory_name,
            $this->service->id
        );
    }

    public function delete(): bool
    {
        $service = $this->service;
        $sourceSystem = $service->getSourceSystem();
        $connector = $service->sourceConnector();
        $authService = SystemAuth::driver($sourceSystem->factory_name, $connector->fabricFormat());
        $webhookService = SystemWebhook::driver($sourceSystem->factory_name, [], $authService);
        if (!$webhookService->unsubscribe($this->remote_reference)) {
            throw new UnsubscribeWebhookException(sprintf('Failed to unsubscribe webhook %s from %s', $this->id, $sourceSystem->name), 500);
        }

        return parent::delete();
    }
}
