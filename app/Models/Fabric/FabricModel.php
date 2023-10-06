<?php

namespace App\Models\Fabric;

use Illuminate\Database\Eloquent\Model;

abstract class FabricModel extends Model
{
    /**
     * The database connection that should be used by the model.
     *
     * @var string
     */
    protected $connection = 'mysql';

    /**
     * Create a new Eloquent model instance.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        if (app()->runningUnitTests()) {
            $this->connection = 'sqlite';
        }
    }
}
