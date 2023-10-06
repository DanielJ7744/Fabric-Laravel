<?php

namespace App\Models\Fabric;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class FactorySystem extends FabricModel
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
        'direction',
        'factory_id',
        'system_id',
        'entity_id',
        'default_map_name',
        'integration_id',
        'display_name',
    ];

    /**
     * Service templates using this factory system as the source_factory_system_id
     *
     * @return HasMany
     */
    public function source(): HasMany
    {
        return $this->hasMany(ServiceTemplate::class, 'source_factory_system_id');
    }

    /**
     * Service templates using this factory system as the destination_factory_system_id
     *
     * @return HasMany
     */
    public function destination(): HasMany
    {
        return $this->hasMany(ServiceTemplate::class, 'destination_factory_system_id');
    }

    /**
     * The entity belonging to this factory system
     *
     * @return BelongsTo
     */
    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }

    /**
     * The system belonging to this factory system
     *
     * @return BelongsTo
     */
    public function system(): BelongsTo
    {
        return $this->belongsTo(System::class);
    }

    /**
     * The factory belonging to this factory system
     *
     * @return BelongsTo
     */
    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class);
    }

    /**
     * Filter fields using this factory system as the factory_system_id
     *
     * @return HasMany
     */
    public function filterField(): HasMany
    {
        // TODO: Rename to filterFields()
        return $this->hasMany(FilterField::class, 'factory_system_id');
    }

    /**
     * Filter templates using this factory system as the factory_system_id
     *
     * @return HasMany
     */
    public function filterTemplate(): HasMany
    {
        // TODO: Rename to filterTemplates()
        return $this->hasMany(FilterTemplate::class, 'factory_system_id');
    }

    /**
     * Get the factories for the FactorySystem.
     *
     * @return BelongsToMany
     */
    public function factories(): BelongsToMany
    {
        return $this->belongsToMany(Factory::class, 'factory_system', 'factory_id')
            ->using(FactorySystem::class)
            ->as('factories');
    }

    /**
     * Get the systems for the FactorySystem.
     *
     * @return BelongsToMany
     */
    public function systems(): BelongsToMany
    {
        return $this->belongsToMany(System::class, 'factory_system', 'system_id')
            ->using(FactorySystem::class)
            ->as('systems');
    }

    public function getDefaultFilters(): Collection
    {
        return FilterField::where('factory_system_id', $this->id)->where('default', true)->get()
            ->map(function ($filterField) {
                $operator = FilterOperator::firstWhere('id', $filterField->default_operator_id);
                return [
                    'id' => sprintf('%s %s', $filterField->key, $operator->key),
                    'value' => $filterField->default_value,
                    'type' => [
                        'id' => $filterField->default_type_id
                    ]
                ];
            });
    }

    /**
     * The factory system schemas owned by the factory system
     *
     * @return HasMany
     */
    public function schemas(): HasMany
    {
        return $this->hasMany(FactorySystemSchema::class);
    }

    /**
     * Get the integration that this factory system belongs to
     *
     * @return BelongsTo
     */
    public function integration(): BelongsTo
    {
        return $this->belongsTo(Integration::class);
    }

    /**
     * Get the service options
     *
     * @return BelongsToMany
     */
    public function serviceOption(): BelongsToMany
    {
        return $this->belongsToMany(ServiceOption::class)->using(FactorySystemServiceOption::class)->withPivot([
            'id',
            'value',
            'user_configurable',
            'properties',
        ]);
    }
}
