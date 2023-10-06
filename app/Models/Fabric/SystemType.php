<?php

namespace App\Models\Fabric;

use App\Models\Fabric\System;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Auditable as IsAuditable;
use OwenIt\Auditing\Contracts\Auditable;

class SystemType extends Model implements Auditable
{
    use IsAuditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'active'];

    /**
     * Get the systems for the system type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function systems(): HasMany
    {
        return $this->hasMany(System::class);
    }
}
