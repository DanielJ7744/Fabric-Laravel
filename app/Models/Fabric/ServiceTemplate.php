<?php

namespace App\Models\Fabric;

use App\Queries\FactorySystemServiceOptionQuery;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class ServiceTemplate extends FabricModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'integration_id',
        'source_factory_system_id',
        'destination_factory_system_id',
        'integration_id',
        'enabled'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'enabled' => 'boolean',
    ];

    /**
     * The source factory system belonging to this service template
     *
     * @return BelongsTo
     */
    public function source(): BelongsTo
    {
        return $this->belongsTo(FactorySystem::class, 'source_factory_system_id');
    }

    /**
     * The destination factory system belonging to this service template
     *
     * @return BelongsTo
     */
    public function destination(): BelongsTo
    {
        return $this->belongsTo(FactorySystem::class, 'destination_factory_system_id');
    }

    /**
     * The source factory belonging to this service template's source factory system
     *
     * @return HasOneThrough
     */
    public function sourceFactory(): HasOneThrough
    {
        return $this->hasOneThrough(Factory::class, FactorySystem::class, 'id', 'id', 'source_factory_system_id', 'factory_id');
    }

    /**
     * The source system belonging to this service template's source factory system
     *
     * @return HasOneThrough
     */
    public function sourceSystem(): HasOneThrough
    {
        return $this->hasOneThrough(System::class, FactorySystem::class, 'id', 'id', 'source_factory_system_id', 'system_id');
    }

    /**
     * The destination factory belonging to this service template's destination factory system
     *
     * @return HasOneThrough
     */
    public function destinationFactory(): HasOneThrough
    {
        return $this->hasOneThrough(Factory::class, FactorySystem::class, 'id', 'id', 'destination_factory_system_id', 'factory_id');
    }

    /**
     * The destination system belonging to this service template's destination factory system
     *
     * @return HasOneThrough
     */
    public function destinationSystem(): HasOneThrough
    {
        return $this->hasOneThrough(System::class, FactorySystem::class, 'id', 'id', 'destination_factory_system_id', 'system_id');
    }

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
     * The source service options belonging to this service template
     *
     * @return Collection
     */
    public function sourceServiceOptions(): Collection
    {
        $options = $this->serviceTemplateOptions()
            ->with('serviceOption')
            ->where('target', 'source')
            ->get();
        if ($options->count() === 0) {
            return (new FactorySystemServiceOptionQuery())->whereBelongsTo($this->source)->get();
        }

        return $options;
    }

    /**
     * The destination service options belonging to this service template
     *
     * @return Collection
     */
    public function destinationServiceOptions(): Collection
    {
        $options = $this->serviceTemplateOptions()
            ->with('serviceOption')
            ->where('target', 'destination')
            ->get();
        if ($options->count() === 0) {
            return (new FactorySystemServiceOptionQuery())->whereBelongsTo($this->destination)->get();
        }

        return $options;
    }

    /**
     * The source service options belonging to this service template formatted as Key-Value pairs
     *
     * @return array
     */
    public function tapestrySourceServiceOptions(): array
    {
        return $this->sourceServiceOptions()->mapWithKeys(function ($option) {
            return [$option->serviceOption->key => $option->value];
        })->toArray();
    }

    /**
     * The destination service options belonging to this service template formatted as Key-Value pairs
     *
     * @return array
     */
    public function tapestryDestinationServiceOptions(): array
    {
        return $this->destinationServiceOptions()->mapWithKeys(function ($option) {
            return [$option->serviceOption->key => $option->value];
        })->toArray();
    }

    /**
     * Get the integration for the service template
     *
     * @return BelongsTo
     */
    public function integration(): BelongsTo
    {
        return $this->belongsTo(Integration::class);
    }

    /**
     * Scope the integration ID query
     *
     * @param Builder $query
     * @param $id
     *
     * @return Builder
     */
    public function scopeIntegrationId(Builder $query, $id): Builder
    {
        return $query->where(
            fn ($query) =>
            $query->where('integration_id', $id)->orWhereNull('integration_id')
        );
    }
}
