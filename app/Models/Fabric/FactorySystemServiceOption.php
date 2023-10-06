<?php

namespace App\Models\Fabric;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FactorySystemServiceOption extends FabricPivot
{
    /**
     * Defines record count for pagination
     *
     * @var int
     */
    protected $perPage = 100;

    /**
     * Defines if model uses auto-incrementing PK ID
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * Defines if model uses timestamp columns
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'factory_system_id',
        'service_option_id',
        'value',
        'user_configurable',
    ];

    protected $casts = [
        'user_configurable' => 'boolean',
        'properties' => 'array'
    ];

    /**
     * Get the factory system
     *
     * @return BelongsTo
     */
    public function factorySystem(): BelongsTo
    {
        return $this->belongsTo(FactorySystem::class);
    }

    /**
     * Get the service option
     *
     * @return BelongsTo
     */
    public function serviceOption(): BelongsTo
    {
        return $this->belongsTo(ServiceOption::class);
    }
}
