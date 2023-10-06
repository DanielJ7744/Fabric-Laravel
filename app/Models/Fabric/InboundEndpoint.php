<?php

namespace App\Models\Fabric;

use App\Models\Fabric\Integration;
use App\Models\Tapestry\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;
use Laravel\Passport\Passport;

class InboundEndpoint extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug',
        'service_id',
        'integration_id',
        'external_endpoint_id' // temporary
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'service_id' => 'integer',
        'integration_id' => 'integer',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['url', 'oauth_url'];

    /**
     * Get the company for the endpoint.
     *
     * @return BelongsTo
     */
    public function integration(): BelongsTo
    {
        return $this->belongsTo(Integration::class);
    }

    /**
     * Get the service for the endpoint.
     *
     * @return BelongsTo
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }


    /**
     * Get the url for the endpoint.
     *
     * @return string
     */
    public function getUrlAttribute(): string
    {
        return Cache::remember(
            "inbound_endpoint_url.$this->id",
            now()->addHour(),
            fn () => sprintf('%s/v1/%s/%s', config('inbound.url'), $this->integration->slug, $this->slug)
        );
    }
}
