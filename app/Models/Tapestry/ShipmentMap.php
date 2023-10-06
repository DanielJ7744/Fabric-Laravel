<?php

namespace App\Models\Tapestry;

use App\Models\Fabric\Integration;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShipmentMap extends TapestryModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table;

    /**
     * The number of models to return for pagination.
     *
     * @var int
     */
    protected $perPage = 50;

    /**
     * Get the integrations for the sync.
     *
     * @return BelongsTo
     */
    public function integration(): BelongsTo
    {
        return $this->belongsTo(Integration::class, 'company_username', 'username');
    }
}
