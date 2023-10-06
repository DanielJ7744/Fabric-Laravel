<?php

namespace App\Models\Alerting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Auditable as IsAuditable;
use OwenIt\Auditing\Contracts\Auditable;

class AlertScheduler extends Model implements Auditable
{
    use IsAuditable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'alert_scheduler';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['config_id', 'frequency', 'active'];

    /**
     * Get the alert configs for the alert scheduler.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function configs(): HasMany
    {
        return $this->hasMany(AlertGroups::class, 'config_id', 'id');
    }
}
