<?php

namespace App\Models\Fabric;

use Illuminate\Database\Eloquent\Model;

class SocialAccount extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'provider',
        'provider_user_id',
    ];

    /**
     * The supported SSO providers.
     *
     * @var array
     */
    public static $providers = [
        'google'
    ];
}
