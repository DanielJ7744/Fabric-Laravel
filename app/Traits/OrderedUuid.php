<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait OrderedUuid
{
    /**
     * Boot the OrderedUuid trait.
     */
    public static function bootOrderedUuid()
    {
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = $model->{$model->getKeyName()} ?: (string) Str::orderedUuid();
        });
    }

    /**
     * Get the value indicating whether the IDs are incrementing.
     *
     * @return bool
     */
    public function getIncrementing()
    {
        return false;
    }

    /**
     * Get the auto-incrementing key type.
     *
     * @return string
     */
    public function getKeyType()
    {
        return 'string';
    }
}
