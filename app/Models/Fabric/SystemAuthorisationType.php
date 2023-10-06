<?php

namespace App\Models\Fabric;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Auditable as IsAuditable;
use OwenIt\Auditing\Contracts\Auditable;

class SystemAuthorisationType extends Model implements Auditable
{
    use IsAuditable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_authorisation_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'system_id',
        'authorisation_type_id',
        'credentials_schema'
    ];

    /**
     * Get the system for the system authorisation type.
     *
     * @return BelongsTo
     */
    public function system(): BelongsTo
    {
        return $this->belongsTo(System::class, 'system_id', 'id');
    }

    /**
     * Get the authorisation type for the system authorisation type.
     *
     * @return BelongsTo
     */
    public function authorisationType(): BelongsTo
    {
        return $this->belongsTo(AuthorisationType::class, 'authorisation_type_id', 'id');
    }
}
