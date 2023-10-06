<?php

namespace App\Models\Fabric;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Factory extends FabricModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get the systems for the factory.
     *
     * @return BelongsToMany
     */
    public function systems(): BelongsToMany
    {
        return $this->belongsToMany(System::class, 'factory_system')->withPivot('direction')->using(FactorySystem::class);
    }
}
