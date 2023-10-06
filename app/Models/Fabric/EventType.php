<?php

namespace App\Models\Fabric;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventType extends FabricModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'key',
        'schema_values',
        'system_id',
    ];

    /**
     * Get the integration for the webhook.
     *
     * @return BelongsTo
     */
    public function system(): BelongsTo
    {
        return $this->belongsTo(System::class);
    }
}
