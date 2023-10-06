<?php

namespace App\Models\Tapestry;

use Illuminate\Database\Eloquent\Model;

abstract class TapestryModel extends Model
{
    /**
     * The database connection that should be used by the model.
     *
     * @var string
     */
    protected $connection = 'mysql_tapestry';

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
