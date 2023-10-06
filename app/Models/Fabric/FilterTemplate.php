<?php

namespace App\Models\Fabric;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FilterTemplate extends Model
{
    /**
     * Defines record count for pagination
     *
     * @var int
     */
    protected $perPage = 100;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'factory_system_id',
        'filter_key',
        'template',
        'note',
        'pw_value_field'
    ];

    /**
     * Get the system for the filter template.
     *
     * @return BelongsTo
     */
    public function factorySystem(): BelongsTo
    {
        return $this->belongsTo(FactorySystem::class);
    }
}
