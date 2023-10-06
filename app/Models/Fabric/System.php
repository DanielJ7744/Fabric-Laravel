<?php

namespace App\Models\Fabric;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use OwenIt\Auditing\Auditable as IsAuditable;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class System extends FabricModel implements Auditable, HasMedia
{
    use IsAuditable;
    use InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'website',
        'description',
        'status',
        'system_type_id',
        'popular',
        'factory_name',
        'time_zone',
        'date_format',
        'documentation_link',
        'has_webhooks',
        'webhook_schema',
        'documentation_link_description',
        'environment_suffix_title'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'popular'         => 'boolean',
        'webhook_schema'  => 'array',
        'system_type_id'  => 'integer',
    ];

    /**
     * Get the system type for the system.
     *
     * @return BelongsTo
     */
    public function systemType(): BelongsTo
    {
        return $this->belongsTo(SystemType::class);
    }

    /**
     * Get the system authorisation types for the system
     *
     * @return HasMany
     */
    public function systemAuthorisationTypes(): HasMany
    {
        return $this->hasMany(SystemAuthorisationType::class);
    }

    /**
     * Get the factories for the system.
     *
     * @return BelongsToMany
     */
    public function factories(): BelongsToMany
    {
        return $this->belongsToMany(Factory::class, 'factory_system')
            ->withPivot('direction')
            ->using(FactorySystem::class);
    }

    /**
     * Scope systems that pull entities from the given systems.
     *
     * @param Builder $query
     * @param  mixed $systems
     * @return Builder
     */
    public function scopePullsFrom(Builder $query, $systems): Builder
    {
        return $query->whereHas('entities', fn (Builder $query) => $query
            ->where('system_entity.direction', 'pull')
            ->whereIn('system_entity.system_id', $this->extractIds($systems)));
    }

    /**
     * Scope systems that push entities to the given systems.
     *
     * @param Builder $query
     * @param  mixed $systems
     * @return Builder
     */
    public function scopePushesTo(Builder $query, $systems): Builder
    {
        return $query->whereHas('entities', fn (Builder $query) => $query
            ->where('system_entity.direction', 'push')
            ->whereIn('system_entity.system_id', $this->extractIds($systems)));
    }

    /**
     * Ensure that the input converts to an array of ids.
     *
     * @param  mixed  $input
     * @return array
     */
    protected function extractIds($input): array
    {
        switch (true) {
            case is_array($input):
                return $input;
            case $input instanceof Model:
                return [$input->getKey()];
            case $input instanceof Collection:
                return $input->pluck('id');
            case str_contains($input, ','):
                return explode(',', $input);
            default:
                return [$input];
        }
    }

    /**
     * Register the media collection belonging to the model
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('logo')
            ->singleFile();
    }

    /**
     * Get the media URL for the system
     *
     * @return string
     */
    public function getMediaUrl(): string
    {
        return Cache::remember(sprintf('system.media_url.%s', $this->id), now()->addHour(), fn () => $this->getFirstMediaUrl('logo'));
    }
}
