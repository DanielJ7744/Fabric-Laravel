<?php

namespace App\Models\Fabric;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Entity extends FabricModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'integration_id'
    ];

    /**
     * Factory systems using this entity as the entity_id
     *
     * @return BelongsToMany
     */
    public function factorySystem(): BelongsToMany
    {
        return $this->belongsToMany(FactorySystem::class);
    }

    /**
     * Get the integration that the entity belongs to
     *
     * @return BelongsTo
     */
    public function integration(): BelongsTo
    {
        return $this->belongsTo(Integration::class);
    }
}
