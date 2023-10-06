<?php

namespace App\Models\Fabric;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FilterField extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'key',
        'factory_system_id',
        'default',
        'default_value',
        'default_type_id',
        'default_operator_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'default' => 'boolean',
    ];

    /**
     * Get the system for the filter field.
     *
     * @return BelongsTo
     */
    public function factorySystem(): BelongsTo
    {
        return $this->belongsTo(FactorySystem::class);
    }

    /**
     * Get the filter types for the filter field.
     *
     * @return BelongsToMany
     */
    public function filterType(): BelongsToMany
    {
        // TODO: rename to filterTypes()
        return $this->belongsToMany(FilterType::class);
    }

    /**
     * Get the filter operators for the filter field.
     *
     * @return BelongsToMany
     */
    public function filterOperator(): BelongsToMany
    {
        // TODO: rename to filterOperators()
        return $this->belongsToMany(FilterOperator::class);
    }

    /**
     * Get the default operator for the filter field
     *
     * @return BelongsTo
     */
    public function defaultOperator(): BelongsTo
    {
        return $this->belongsTo(FilterOperator::class);
    }

    /**
     * Get the default type for the filter field
     *
     * @return BelongsTo
     */
    public function defaultType(): BelongsTo
    {
        return $this->belongsTo(FilterType::class);
    }
}
