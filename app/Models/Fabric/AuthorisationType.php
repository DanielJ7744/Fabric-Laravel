<?php

namespace App\Models\Fabric;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use OwenIt\Auditing\Auditable as IsAuditable;
use OwenIt\Auditing\Contracts\Auditable;

class AuthorisationType extends Model implements Auditable
{
    use IsAuditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Get the systems for the authorisation type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function system(): BelongsToMany
    {
        return $this->belongsToMany(System::class);
    }
}
