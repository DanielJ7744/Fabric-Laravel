<?php

namespace App\Models\Tapestry;

use App\Enums\Systems;
use App\Http\Helpers\ElasticsearchHelper;
use App\Http\Helpers\ServiceFilterHelper;
use App\Http\Interfaces\EventLogInterface;
use App\Models\Alerting\AlertConfigs;
use App\Models\Fabric\Entity;
use App\Models\Fabric\Factory;
use App\Models\Fabric\FactorySystem;
use App\Models\Fabric\FilterTemplate;
use App\Models\Fabric\Integration;
use App\Models\Fabric\ServiceTemplate;
use App\Models\Fabric\System;
use App\Models\Fabric\Webhook;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use OwenIt\Auditing\Auditable as IsAuditable;
use OwenIt\Auditing\Contracts\Auditable;
use Throwable;

class Service extends TapestryModel implements Auditable, EventLogInterface
{
    use SoftDeletes, IsAuditable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'service';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status',
        'schedule',
        'billable',
        'username',
        'to_options',
        'to_factory',
        'to_mapping',
        'description',
        'from_options',
        'from_mapping',
        'from_factory',
        'to_environment',
        'from_environment',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'boolean',
        'to_options' => 'array',
        'filterable' => 'boolean',
        'from_options' => 'array',
        'dashboard_visibility' => 'boolean',
    ];

    /**
     * The number of models to return for pagination.
     *
     * @var int
     */
    protected $perPage = 50;

    /**
     * Get the integrations for the service.
     *
     * @return BelongsTo
     */
    public function integration(): BelongsTo
    {
        return $this->belongsTo(Integration::class, 'username', 'username');
    }

    /**
     * Get the source connector for the service.
     *
     * @return Connector
     */
    public function sourceConnector(): ?Connector
    {
        $sourceSystem = $this->getSourceSystem();

        if (!$sourceSystem) {
            return null;
        }

        try {
            return (new Connector())
                ->setIdxTable($this->username)
                ->where('common_ref', $this->from_environment)
                ->where('system_chain', $sourceSystem->factory_name)
                ->first();
        } catch (Exception $exception) {
            return null;
        }
    }

    /**
     * Get the destination connector for the service.
     *
     * @return Connector
     */
    public function destinationConnector(): ?Connector
    {
        $destinationSystem = $this->getDestinationSystem();
        if (!$destinationSystem) {
            return null;
        }

        try {
            return (new Connector())
                ->setIdxTable($this->username)
                ->where('common_ref', $this->to_environment)
                ->where('system_chain', $destinationSystem->factory_name)
                ->first();
        } catch (Exception $exception) {
            return null;
        }
    }

    /**
     * Get the alert config for the service
     *
     * @return HasOne
     */
    public function alertConfigs(): HasOne
    {
        return $this->hasOne(AlertConfigs::class, 'service_id', 'id');
    }

    public static function getArea(): string
    {
        return 'services';
    }

    /**
     * Scope a query to active services.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query): Builder
    {
        return $query->where('status', true);
    }

    /**
     * Scope a query by billable status.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  bool  $bool
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBillable($query, $bool = true): Builder
    {
        return $query->where('billable', $bool);
    }

    /**
     * Scope a query by usernames.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  iterable  $usernames
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUsernames($query, iterable $usernames): Builder
    {
        return $query->whereIn('username', $usernames);
    }

    /**
     * Merge the given attributes with the existing attributes.
     *
     * @param array|null $originalValues
     * @param array|null $values
     * @return array
     */
    public function mergeFromOptions(?array $originalValues, ?array $values): array
    {
        if (is_null($originalValues) && is_null($values)) {
            return [];
        }

        if (is_null($values)) {
            return $originalValues;
        }

        if (is_null($originalValues)) {
            $originalValues = [];
        }

        foreach ($values as $valueKey => $value) {
            if (!Str::contains($valueKey, 'filters')) {
                continue;
            }
            $originalValues[$valueKey] = $value;
        }

        return $originalValues;
    }

    /**
     * Disable the service.
     *
     * @return self
     */
    public function disable(): self
    {
        $this->status = false;

        return tap($this)->save();
    }

    public function getSourceEntity(): ?Entity
    {
        return $this->getEntityByFactory($this->from_factory);
    }

    public function getDestinationEntity(): ?Entity
    {
        return $this->getEntityByFactory($this->to_factory);
    }

    public function getSourceFactorySystem(): ?FactorySystem
    {
        return $this->serviceTemplate
            ? $this->serviceTemplate->source
            : $this->getFactorySystem($this->from_factory);
    }

    public function getDestinationFactorySystem(): ?FactorySystem
    {
        return $this->serviceTemplate
            ? $this->serviceTemplate->destination
            : $this->getFactorySystem($this->to_factory);
    }

    public function getFilterTemplate(): ?FilterTemplate
    {
        $factorySystem = $this->getFactorySystem($this->from_factory);

        if (!$factorySystem) {
            return null;
        }

        return FilterTemplate::firstWhere('factory_system_id', $factorySystem->id);
    }

    /**
     * Get the factory system
     *
     * @param string $serviceFactory
     *
     * @return FactorySystem|null
     */
    protected function getFactorySystem(string $serviceFactory): ?FactorySystem
    {
        if (!$this->isValidServiceFactory($serviceFactory)) {
            return null;
        }

        $cacheKey = sprintf('factory.system.%s', $serviceFactory);

        return Cache::remember($cacheKey, now()->addHour(), function () use ($serviceFactory) {
            $explodedFactory = explode('\\', $serviceFactory);
            $system = $explodedFactory[0];
            $direction = $explodedFactory[1];
            $factory = count($explodedFactory) > 3 ? sprintf('%s\%s', $explodedFactory[2], $explodedFactory[3]) : $explodedFactory[2];
            if (!Factory::firstWhere('name', $factory) && isset($explodedFactory[3])) {
                $factory = $explodedFactory[3];
            }

            $system = System::firstWhere('factory_name', $system);
            $factory = Factory::firstWhere('name', $factory);
            if (!$system || !$factory) {
                return null;
            }

            return FactorySystem::firstWhere([
                'system_id' => $system->id,
                'factory_id' => $factory->id,
                'direction' => mb_strtolower($direction)
            ]);
        });
    }

    protected function getEntityByFactory(string $serviceFactory): ?Entity
    {
        $factorySystem = $this->getFactorySystem($serviceFactory);

        return Cache::remember(sprintf('service-factory.entity.%s', $serviceFactory), now()->addHour(), fn () => $factorySystem ? Entity::find($factorySystem->entity_id) : null);
    }

    public function getSourceSystem(): ?System
    {
        return $this->getSystemByFactory($this->from_factory);
    }

    public function getDestinationSystem(): ?System
    {
        return $this->getSystemByFactory($this->to_factory);
    }

    protected function getSystemByFactory(string $serviceFactory): ?System
    {
        if (!$this->isValidServiceFactory($serviceFactory)) {
            return null;
        }

        [$system] = explode('\\', $serviceFactory);

        $cacheKey = sprintf('system.from.factory.%s', $system);

        return Cache::remember($cacheKey, now()->addDay(), fn () => System::firstWhere('factory_name', $system));
    }

    protected function isValidServiceFactory($serviceFactory): bool
    {
        return count(explode('\\', $serviceFactory)) >= 3;
    }

    /**
     * Filters are an array within the from options JSON.
     * This method is called to set the relevant JSON within the from options JSON.
     *
     * @param array $filters
     * @param FactorySystem $factorySystem
     *
     * @return array
     */
    public function formatFilters(array $filters, FactorySystem $factorySystem): array
    {
        if (ServiceFilterHelper::isJSONFilterStructure($filters)) {
            return $filters;
        }

        return ServiceFilterHelper::destructFilters($factorySystem, collect($filters));
    }

    /**
     * Clone the default mapping files
     *
     * @param Integration $integration
     * @param ServiceTemplate $serviceTemplate
     * @param ElasticsearchHelper $elasticsearchHelper
     *
     * @return array
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function cloneDefaultMappings(Integration $integration, ServiceTemplate $serviceTemplate, ElasticsearchHelper $elasticsearchHelper): array
    {
        $fromMapping = null;
        $toMapping = null;
        $sourceSystem = $serviceTemplate->sourceSystem;
        $sourceFactorySystem = $serviceTemplate->source;
        $destinationFactorySystem = $serviceTemplate->destination;
        if (!is_null($sourceFactorySystem->default_map_name)) {
            $fromMapping = self::cloneMap($elasticsearchHelper, $sourceFactorySystem, $integration);
        }

        if (is_null($sourceFactorySystem->default_map_name) && in_array($sourceSystem->name, [Systems::SFTP, Systems::INBOUND_API])) {
            $fromMapping = self::createMappingFile($elasticsearchHelper, $sourceFactorySystem, $integration, '{}');
        }

        if (!is_null($destinationFactorySystem->default_map_name)) {
            $toMapping = self::cloneMap($elasticsearchHelper, $destinationFactorySystem, $integration);
        }

        return ['from_mapping' => $fromMapping, 'to_mapping' => $toMapping];
    }

    /**
     * Build the mapping file name from the factory system
     *
     * @param FactorySystem $factorySystem
     *
     * @return string
     */
    private static function getMappingName(FactorySystem $factorySystem): string
    {
        return preg_replace(
            '/\s+/',
            '_',
            Str::lower(sprintf(
                '%s_%s_%s',
                $factorySystem->system->factory_name,
                $factorySystem->direction,
                $factorySystem->entity->name
            ))
        );
    }

    /**
     * Get the default mapping file
     *
     * @param ElasticsearchHelper $elasticsearchHelper
     * @param string $mapName
     *
     * @return array
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private static function getDefaultMap(ElasticsearchHelper $elasticsearchHelper, string $mapName): array
    {
        return $elasticsearchHelper->get(sprintf('mappings/storage/%s', $mapName));
    }

    /**
     * Get the payload required to create a mapping file
     *
     * @param string $username
     * @param string $mappingName
     * @param string $createdAt
     * @param $content
     *
     * @return array
     */
    private static function getMappingPayload(string $username, string $mappingName, string $createdAt, $content): array
    {
        $searchField = sprintf('%s_%s', $username, $mappingName);

        return [
            'username' => $username,
            'search_field' => $searchField,
            'mapping_name' => $mappingName,
            'created_at' => $createdAt,
            'content' => $content,
        ];
    }

    /**
     * Clone the default mapping file after finding it using the factory system passed in
     *
     * @param ElasticsearchHelper $elasticsearchHelper
     * @param FactorySystem $factorySystem
     * @param Integration $integration
     *
     * @return string|null
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private static function cloneMap(
        ElasticsearchHelper $elasticsearchHelper,
        FactorySystem $factorySystem,
        Integration $integration
    ): ?string {
        try {
            $defaultMap = self::getDefaultMap($elasticsearchHelper, $factorySystem->default_map_name);
        } catch (Throwable $throwable) {
            Log::error(sprintf('Failed cloning mapping files. Error: %s', $throwable->getMessage()));

            return null;
        }

        $mapContent = $defaultMap['_source']['content'];

        return self::createMappingFile($elasticsearchHelper, $factorySystem, $integration, $mapContent);
    }

    /**
     * Attempt to create a mapping file within Elastic Search
     *
     * @param ElasticsearchHelper $elasticsearchHelper
     * @param FactorySystem $factorySystem
     * @param Integration $integration
     * @param $content
     *
     * @return string|null
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private static function createMappingFile(
        ElasticsearchHelper $elasticsearchHelper,
        FactorySystem $factorySystem,
        Integration $integration,
        $content
    ): ?string {
        $createdAt = Carbon::now()->toRfc3339String();
        $mappingPayload = self::getMappingPayload(
            $integration->username,
            self::getMappingName($factorySystem),
            $createdAt,
            $content
        );
        $id = sprintf('%s_%s', $integration->username, self::getMappingName($factorySystem));
        $fullMappingName = sprintf('mappings/storage/%s', $id);
        $fullArchiveMappingName = sprintf('mappings-archive/storage/%s-%s', $id, $createdAt);
        try {
            $source = $elasticsearchHelper->get($fullMappingName)['_source'];
            $elasticsearchHelper->post($fullArchiveMappingName, $source);
            $elasticsearchHelper->put($fullMappingName, $mappingPayload);

            return self::getMappingName($factorySystem);
        } catch (Exception $exception) {
            if ($exception instanceof ClientException && $exception->getResponse()->getStatusCode() === 404) {
                try {
                    $elasticsearchHelper->post($fullMappingName, $mappingPayload);

                    return self::getMappingName($factorySystem);
                } catch (Exception $exception) {
                    return null;
                }
            }
        }

        return null;
    }

    /**
     * Get the webhooks for a service
     *
     * @return HasMany
     */
    public function webhooks(): HasMany
    {
        return $this->hasMany(Webhook::class);
    }

    /**
     * Get the service template for the service
     *
     * @return BelongsTo
     */
    public function serviceTemplate(): BelongsTo
    {
        return $this->belongsTo(ServiceTemplate::class);
    }
}
