<?php

namespace App\Models\Tapestry;

use App\Enums\AuthTypes;
use App\Facades\ConnectorAuth;
use App\Http\Interfaces\EventLogInterface;
use App\Models\Fabric\Integration;
use App\Models\Fabric\System;
use App\Models\Scopes\Tapestry\ConnectorScope;
use App\Models\Tapestry\TapestryModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Laravel\Passport\Passport;
use OwenIt\Auditing\Auditable as IsAuditable;
use OwenIt\Auditing\Contracts\Auditable;

class Connector extends TapestryModel implements Auditable, EventLogInterface
{
    use SoftDeletes, IsAuditable;

    public const TYPE = 'Credentials';

    public const ADDITIONAL_FIELDS = [
        'authorisation_type' => 'authorisation_type',
        'connector_name' => 'connectorName',
        'timezone' => 'timeZone',
        'date_format' => 'dateFormat',
    ];

    protected $table;

    protected $attributes = [
        'type' => self::TYPE
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'extra',
        'deleted_at',
        'common_ref',
        'system_chain',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'extra' => 'array',
        'common_ref' => 'string',
        'system_chain' => 'string',
    ];

    /**
     * Defines record count for pagination
     *
     * @var int
     */
    protected $perPage = 50;

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ConnectorScope);
    }

    public function setIdxTable(string $username): Connector
    {
        $integration = Integration::where('username', $username)->firstOrFail();

        $this->table = sprintf('idx_%s', $integration->username);

        return $this;
    }

    public static function getArea(): string
    {
        return 'connector';
    }

    public function getIdAttribute(int $value): string
    {
        return sprintf('%s|%s', mb_substr($this->getTable(), 4), $value);
    }

    /**
     * Get the system chain for the connector
     *
     * @param string $value
     *
     * @return string
     */
    public function getSystemChainAttribute(string $value): string
    {
        return ucfirst($value);
    }

    /**
     * Get the system for the connector
     *
     * @return BelongsTo
     */
    public function system(): BelongsTo
    {
        return $this->belongsTo(System::class, 'system_chain', 'factory_name');
    }

    /**
     * Get the integration for the connector.
     *
     * @return BelongsTo
     */
    public function integration(): BelongsTo
    {
        return $this->belongsTo(Integration::class, 'username', 'username');
    }

    /**
     * Get the integration for the connector.
     *
     * @return BelongsToMany
     */
    public function clients(): BelongsToMany
    {
        return $this
            ->belongsToMany(Passport::clientModel())
            ->withPivot(['safe_secret']);
    }

    /**
     * Get the integration for the connector
     *
     * @return Integration|null
     */
    public function getIntegration(): ?Integration
    {
        $integrations = Cache::remember('connector.resource.integrations', 0, fn () => Integration::all()->keyBy('username'));

        return $integrations[mb_substr($this->getTable(), 4)] ?? null;
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @return \App\Models\Tapestry\Connector|null
     */
    public function resolveRouteBinding($value): ?Connector
    {
        [$username, $id] = explode('|', $value);

        $integration = Integration::whereUsername($username)->firstOrFail();

        return $this->setIdxTable($integration->username)->findOrFail($id);
    }

    public static function mergeAdditionalCredentialFields(array $credentials, array $additionalFields): array
    {
        foreach (Connector::ADDITIONAL_FIELDS as $tapestryKey => $requestKey) {
            if (Arr::has($additionalFields, $requestKey)) {
                $credentials[$tapestryKey] = Arr::get($additionalFields, $requestKey);
            }
        }

        return $credentials;
    }

    public function tapestryFormat(): array
    {
        return ConnectorAuth::getTapestryFormat($this->system->factory_name, $this->extra, $this->authType());
    }

    public function fabricFormat(bool $obfuscate = false): array
    {
        $credentials = ConnectorAuth::getFabricFormat($this->system->factory_name, $this->extra, $this->authType());

        return $obfuscate
            ? $this->obfuscate($credentials, ConnectorAuth::getObfuscatedFields($this->system->factory_name, $this->extra, $this->authType()))
            : $credentials;
    }

    public function authType(): ?string
    {
        return Arr::get($this->extra, 'authorisation_type', AuthTypes::NONE);
    }

    public function transformAudit(array $data): array
    {
        $this->transformExtra($data, 'old_values.extra');
        $this->transformExtra($data, 'new_values.extra');

        return $data;
    }

    private function transformExtra(array &$data, string $extraKey): void
    {
        if (Arr::has($data, $extraKey)) {
            Arr::set($data, $extraKey, self::fabricFormat(true));
        }
    }

    public function obfuscate(array $credentials, array $obfuscatedFields): array
    {
        return collect($credentials)->mapWithKeys(function ($value, $key) use ($obfuscatedFields) {
            return [$key => in_array(strtolower($key), array_map('strtolower', $obfuscatedFields)) ? '*****' : $value];
        })->toArray();
    }

    /**
     * Scope a query to inbound api connectors.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInboundApis($query): Builder
    {
        return $query->whereSystemChain('InboundAPI');
    }
}
