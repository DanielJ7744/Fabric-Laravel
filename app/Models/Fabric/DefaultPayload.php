<?php

namespace App\Models\Fabric;

use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as IsAuditable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DefaultPayload extends FabricModel implements Auditable
{
    use IsAuditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'factory_system_schema_id',
        'type',
        'payload',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the factory system schema for the default payload
     *
     * @return BelongsTo
     */
    public function factorySystemSchema(): BelongsTo
    {
        return $this->belongsTo(FactorySystemSchema::class);
    }
}
