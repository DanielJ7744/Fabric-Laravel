<?php

namespace App\Models\Fabric;

use App\Models\Fabric\Company;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'sftp' => 'boolean',
        'price' => 'integer',
        'users' => 'integer',
        'upgrade' => 'boolean',
        'default' => 'boolean',
        'api_keys' => 'integer',
        'services' => 'integer',
        'transactions' => 'integer',
        'business_insights' => 'boolean',
    ];

    /**
     * Get the companies for the subscription.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class)->withTimestamps();
    }

    /**
     * Scope a query to the default subscription.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDefault($query): Builder
    {
        return $query->where('default', true);
    }
}
