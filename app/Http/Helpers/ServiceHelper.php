<?php

namespace App\Http\Helpers;

use App\Models\Fabric\Entity;
use App\Models\Fabric\FactorySystem;
use App\Models\Fabric\FilterTemplate;
use App\Models\Fabric\System;

class ServiceHelper
{
    public static function getEntityForService(array $service, string $direction): ?Entity
    {
        $factoryString = $service[sprintf('%s_factory', $direction)];
        $entityString = self::getEntityStringFromFactoryString($factoryString);

        return Entity::firstWhere('name', $entityString);
    }

    public static function getSystemForService(array $service, string $direction): ?System
    {
        $factoryString = $service[sprintf('%s_factory', $direction)];

        $systemString = self::getSystemStringFromFactoryString($factoryString);

        return System::firstWhere('factory_name', $systemString);
    }

    public static function getEntityStringFromFactoryString(string $factoryString): string
    {
        $splitFactory = explode('\\', $factoryString);

        return end($splitFactory);
    }

    public static function getSystemStringFromFactoryString(string $factoryString): string
    {
        [$splitFactory] = explode('\\', $factoryString);

        return $splitFactory;
    }

    public static function getFilterTemplateByFactorySystem(FactorySystem $factorySystem): ?FilterTemplate
    {
        return FilterTemplate::query()
            ->where('factory_system_id', $factorySystem->id)
            ->first();
    }

    public static function getBaseFactoryString(string $systemName, string $direction, string $entityFactoryName): string
    {
        return sprintf('%s\%s\%s', $systemName, $direction, $entityFactoryName);
    }
}
