<?php

namespace App\Models\Fabric;

use App\Events\IntegrationCreated;
use App\Events\PasswordUpdateFailed;
use App\Http\Helpers\CompanySettingsHelper;
use App\Models\Tapestry\Service;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Kalnoy\Nestedset\NodeTrait;
use OwenIt\Auditing\Auditable as IsAuditable;
use OwenIt\Auditing\Contracts\Auditable;

class Integration extends FabricModel implements Auditable
{
    use Notifiable, IsAuditable, NodeTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'created_at',
        'updated_at',
        'username',
        'server',
        'active',
        'company_id',
        'parent_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'company_id' => 'integer',
        'active'     => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($integration) {
            if (empty($integration->slug)) {
                $integration->slug = self::generateSlug($integration);
            }
        });
    }

    /**
     * Get the company for the integration.
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the services for the integration.
     *
     * @return HasMany
     */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'username', 'username');
    }

    /**
     * Get the users for an integration
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /**
     * Get the webhooks for an integration
     *
     * @return HasMany
     */
    public function webhooks(): HasMany
    {
        return $this->hasMany(Webhook::class);
    }

    /**
     * Get the inbound endpoints for the company.
     *
     * @return HasMany
     */
    public function endpoints(): HasMany
    {
        return $this->hasMany(InboundEndpoint::class);
    }

    /**
     * Get the factory system schemas for the integration
     *
     * @return HasMany
     */
    public function factorySystemSchemas(): HasMany
    {
        return $this->hasMany(FactorySystemSchema::class);
    }

    /**
     * Get the entities that belong to the integration
     *
     * @return HasMany
     */
    public function entities(): HasMany
    {
        return $this->hasMany(Entity::class);
    }

    /**
     * Get the factory systems for the integration
     *
     * @return HasMany
     */
    public function factorySystems(): HasMany
    {
        return $this->hasMany(FactorySystem::class);
    }

    /**
     * Get the service templates for the integration
     *
     * @return HasMany
     */
    public function serviceTemplates(): HasMany
    {
        return $this->hasMany(ServiceTemplate::class);
    }

    /**
     * Attempt to generate an IDX table within Tapestry
     *
     * @return void
     */
    public function generateIdxTable(): void
    {
        $settingsHelper = App::make(CompanySettingsHelper::class);
        $created = $settingsHelper->createTapestryIdxTable($this->username, $this->server);
        if ($created) {
            event(new IntegrationCreated($this));
        }
    }

    /**
     * Generate a unique slug for an integration.
     *
     * @param Integration $integration
     *
     * @return string
     */
    static function generateSlug(Integration $integration): string
    {
        $original = Str::slug($integration->name);
        $slug = $original;
        $count = 1;

        while (Integration::whereSlug($slug)->exists()) {
            $slug = "{$original}-" . $count++;
        }

        return $slug;
    }
}
