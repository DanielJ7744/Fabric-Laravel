<?php

namespace App\Models\Fabric;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceTemplateOption extends FabricModel
{
    /**
     * Defines if model uses timestamp columns
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'service_option_id',
        'service_template_id',
        'target',
        'value',
        'user_configurable'
    ];

    /**
     * Cast attributes to things
     *
     * @var array
     */
    protected $casts = [
        'value' => 'array'
    ];

    /**
     * Get the service option for the service template option
     *
     * @return BelongsTo
     */
    public function serviceOption(): BelongsTo
    {
        return $this->belongsTo(ServiceOption::class);
    }

    /**
     * Get the service template for the service template option
     *
     * @return BelongsTo
     */
    public function serviceTemplate(): BelongsTo
    {
        return $this->belongsTo(ServiceTemplate::class);
    }
}
