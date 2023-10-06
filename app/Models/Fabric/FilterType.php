<?php

namespace App\Models\Fabric;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FilterType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'placeholder', 'key'];

    /**
     * Get the filter operators for the filter type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function filterOperator(): BelongsToMany
    {
        // TODO: rename to filerOperators
        return $this->belongsToMany(FilterOperator::class, 'filter_type_filter_operator');
    }
}
