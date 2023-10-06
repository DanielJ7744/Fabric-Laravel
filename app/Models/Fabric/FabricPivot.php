<?php

namespace App\Models\Fabric;

use Illuminate\Database\Eloquent\Relations\Pivot;

abstract class FabricPivot extends Pivot
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
