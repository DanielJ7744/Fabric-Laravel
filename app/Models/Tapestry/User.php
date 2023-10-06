<?php

namespace App\Models\Tapestry;

class User extends TapestryModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'server',
        'email',
        'password',
        'alert_email',
        'alert_levels',
        'key',
    ];

    /**
     * Create a new Tapestry User model instance.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        if (app()->runningUnitTests()) {
            $this->table = 'tapestry_users';
        }
    }
}
