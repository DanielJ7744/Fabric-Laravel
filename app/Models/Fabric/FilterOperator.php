<?php

namespace App\Models\Fabric;

use App\Models\Fabric\FilterField;
use App\Models\Fabric\FilterType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FilterOperator extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'key'];

    /**
     * Get the filter fields for the filter operator.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function types(): BelongsToMany
    {
        // TODO: rename pivot table to filter_operator_filter_type, alphabetical is convention
        return $this->belongsToMany(FilterType::class, 'filter_type_filter_operator');
    }

    /**
     * Get the filter fields for the filter operator.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function fields(): BelongsToMany
    {
        return $this->belongsToMany(FilterField::class);
    }
}
