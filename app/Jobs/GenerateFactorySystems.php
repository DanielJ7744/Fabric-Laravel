<?php

namespace App\Jobs;

use Exception;
use Illuminate\Support\Str;
use App\Models\Fabric\Entity;
use App\Models\Fabric\System;
use Illuminate\Bus\Queueable;
use App\Models\Fabric\Factory;
use App\Models\Tapestry\Service;
use Illuminate\Support\Facades\Log;
use App\Models\Fabric\FactorySystem;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GenerateFactorySystems
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @var System
     */
    private System $system;

    /**
     * Job constructor
     *
     * @param System $system
     */
    public function __construct(System $system)
    {
        $this->system = $system;
    }

    /**
     * Get factory systems for a system
     * Get data from database and use it to create a factory system
     *
     * @return void
     */
    public function handle()
    {
        Service::where('from_factory', 'LIKE', '%' . $this->system->factory_name . '%')
            ->orWhere('to_factory', 'LIKE', '%' . $this->system->factory_name . '%')
            ->chunk(100, function ($services) {
                $services->each(function ($service) {
                    try {
                        if (Str::contains($service->from_factory, $this->system->factory_name)) {
                            $this->createFactorySystem($service->from_factory);
                        }
                        if (Str::contains($service->to_factory, $this->system->factory_name)) {
                            $this->createFactorySystem($service->to_factory);
                        }
                    } catch (Exception $exception) {
                        Log::error(sprintf('Failed creating factory system: %s', $exception->getMessage()));
                    }
                });
            });
    }

    /**
     * Create the factory system using the factory string from a passed in service
     *
     * @param string $factoryString
     *
     * @return void
     */
    private function createFactorySystem(string $factoryString): void
    {
        $explodedFactory = explode('\\', $factoryString);
        $direction = strtolower($explodedFactory[1]);
        $factory = count($explodedFactory) > 3 ? sprintf('%s\%s', $explodedFactory[2], $explodedFactory[3]) : $explodedFactory[2];
        $entity = count($explodedFactory) > 3 ? $explodedFactory[3] : $explodedFactory[2];
        if (!Factory::firstWhere('name', $factory) && isset($explodedFactory[3])) {
            $factory = $explodedFactory[3];
        }

        $factory = Factory::where('name', $factory)->firstOrFail();
        $entity = Entity::where('name', $entity)->firstOrFail();

        FactorySystem::updateOrCreate(
            [
                'factory_id' => $factory->id,
                'system_id' => $this->system->id,
                'entity_id' => $entity->id,
                'direction' => $direction,
                'integration_id' => null
            ],
            [
                'factory_id' => $factory->id,
                'system_id' => $this->system->id,
                'entity_id' => $entity->id,
                'direction' => $direction,
                'integration_id' => null,
                'display_name' => $entity->name
            ]
        );
    }
}
