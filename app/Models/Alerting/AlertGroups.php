<?php

namespace App\Models\Alerting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Auditable as IsAuditable;
use OwenIt\Auditing\Contracts\Auditable;

class AlertGroups extends Model implements Auditable
{
    use IsAuditable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'alert_groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'company_id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'company_id' => 'integer',
    ];

    /**
     * Get the recipients for the alert group.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recipients(): HasMany
    {
        return $this->hasMany(AlertRecipients::class, 'group_id', 'id');
    }
}
