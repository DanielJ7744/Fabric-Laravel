<?php

namespace App\Models\Fabric;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceOption extends FabricModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key',
    ];

    /**
     * Get the service template options that the service template has
     *
     * @return HasMany
     */
    public function serviceTemplateOptions(): HasMany
    {
        return $this->hasMany(ServiceTemplateOption::class);
    }

    /**
     * Get the factory systems
     *
     * @return BelongsToMany
     */
    public function factorySystems(): BelongsToMany
    {
        return $this->belongsToMany(FactorySystem::class)->using(FactorySystemServiceOption::class)->withPivot([
            'id',
            'value',
            'user_configurable',
            'properties',
        ]);
    }
}
