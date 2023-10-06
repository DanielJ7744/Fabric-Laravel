<?php

namespace App\Models\Fabric;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Auditable as IsAuditable;
use OwenIt\Auditing\Contracts\Auditable;

class FactorySystemSchema extends FabricModel implements Auditable
{
    use IsAuditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'factory_system_id',
        'integration_id',
        'type',
        'schema',
        'original_type',
        'original_schema',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the factory system for the factory system schema
     *
     * @return BelongsTo
     */
    public function factorySystem(): BelongsTo
    {
        return $this->belongsTo(FactorySystem::class);
    }

    /**
     * Get the company for the factory system schema
     *
     * @return BelongsTo
     */
    public function integration(): BelongsTo
    {
        return $this->belongsTo(Integration::class);
    }

    /**
     * Get the default payload for the factory system schema
     *
     * @return HasOne
     */
    public function defaultPayload(): HasOne
    {
        return $this->hasOne(DefaultPayload::class);
    }
}
