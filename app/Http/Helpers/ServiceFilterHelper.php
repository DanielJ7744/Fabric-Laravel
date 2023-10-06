<?php

namespace App\Http\Helpers;

use App\Exceptions\InvalidFilterFieldException;
use App\Exceptions\InvalidFilterOperatorException;
use App\Exceptions\InvalidFilterValueException;
use App\Models\Fabric\Entity;
use App\Models\Fabric\FactorySystem;
use App\Models\Fabric\FilterOperator;
use App\Models\Fabric\FilterField;
use App\Models\Fabric\FilterType;
use App\Models\Fabric\System;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ServiceFilterHelper
{
    public static function getFromOptionFilters(array $fromOptions): Collection
    {
        return json_last_error() === JSON_ERROR_NONE ? collect(Arr::get($fromOptions, 'filters')) : collect([]);
    }

    public static function constructFilters(FactorySystem $factorySystem, Collection $filters): Collection
    {
        return $filters->map(function ($value, $field) use ($factorySystem) {
            [$field, $type, $value, $operator] = self::validateFilter($field, $value, $factorySystem);

            return [
                'id' => self::getUniqueFilterID($field, $operator),
                'field' => ['id' => $field->id],
                'operator' => ['id' => $operator->id],
                'type' => ['id' => $type->id],
                'value' => self::isTimeFilter($value, $type->key)
                    ? self::getTimeFilterValue($value, $type->key)
                    : $value
            ];
        })->values();
    }

    /**
     * @param string $filterId
     * @param mixed $filterValue
     * @param System $system
     * @param Entity $entity
     * @param FilterType $filterType
     *
     * @return array
     *
     * @throws Exception
     */
    protected static function validateFilter(
        string $filterId,
        $filterValue,
        FactorySystem $factorySystem,
        ?FilterType $filterType = null
    ): array {
        $filterField = self::getFilterField($filterId, $factorySystem->id);
        if ($filterField === null) {
            throw new InvalidFilterFieldException();
        }

        $filterType = self::getFilterFieldType($filterValue, $filterField->filterType, $filterType);
        if ($filterType === null) {
            throw new InvalidFilterValueException();
        }

        $filterOperator = self::getFilterFieldOperator($filterId, $filterField->filterOperator, $filterType->filterOperator);
        if ($filterOperator === null) {
            throw new InvalidFilterOperatorException();
        }

        return [$filterField, $filterType, $filterValue, $filterOperator];
    }

    public static function destructFilters(FactorySystem $factorySystem, Collection $filters): array
    {
        return $filters->mapWithKeys(function ($filter) use ($factorySystem) {
            [$field, $type, $value, $operator] = self::validateFilter(
                $filter['id'],
                $filter['value'],
                $factorySystem,
                FilterType::firstWhere('id', Arr::get($filter, 'type.id'))
            );

            return [self::getUniqueFilterID($field, $operator) => self::formatFilterValue($value, $type)];
        })->toArray();
    }

    protected static function getUniqueFilterID(FilterField $filterField, FilterOperator $filterOperator) {
        return sprintf('%s %s', $filterField->key, $filterOperator->key);
    }

    protected static function getFilterFieldOperator(
        string $field,
        Collection $filterFieldOperators,
        Collection $filterTypeOperators
    ): ?FilterOperator {
        $operator = self::splitFieldAndOperator($field)[1] ?? '=';

        $filterOperator = $filterFieldOperators->first(function ($filterFieldOperator) use ($operator) {
            return $filterFieldOperator->key === $operator;
        });

        if (!is_null($filterOperator)) {
            return $filterOperator;
        }

        return $filterTypeOperators->first(function ($filterTypeOperator) use ($operator) {
            return $filterTypeOperator->key === $operator;
        });
    }

    protected static function getFilterField(string $field, int $factorySystemId): ?FilterField
    {
        $field = self::splitFieldAndOperator($field)[0];

        return FilterField::firstWhere(['key' => $field, 'factory_system_id' => $factorySystemId]);
    }

    protected static function getFilterFieldTypes(FilterField $filterField): Collection
    {
        return $filterField->filterType;
    }

    protected static function splitFieldAndOperator(string $field): array
    {
        return preg_split('/\s+(?=\S*+$)/', $field);
    }

    /**
     * @param mixed $value
     * @param Collection $filterFieldTypes
     * @param FilterType|null $filterType
     *
     * @return FilterType|null
     */
    protected static function getFilterFieldType($value, Collection $filterFieldTypes, ?FilterType $filterType): ?FilterType
    {
        return $filterFieldTypes->first(function ($filterFieldType) use ($value, $filterType) {
            if (!is_null($filterType)) {
                return $filterFieldType->id === Arr::get($filterType, 'id');
            }

            if (
                self::isArrayFilter($value, $filterFieldType->key)
                || self::isTimeFilter($value, $filterFieldType->key)
                || self::isCSVFilter($value, $filterFieldType->key)
                || self::isDecimalFilter($value, $filterFieldType->key)
                || self::isIntegerFilter($value, $filterFieldType->key)
            ) {
                return true;
            }

            return $filterFieldType->key === getType($value);
        });
    }

    /**
     * @param mixed $haystack
     * @param string $filterFieldTypeName
     *
     * @return bool
     */
    protected static function isDecimalFilter($haystack, string $filterFieldTypeName): bool
    {
        return is_numeric($haystack) && floor($haystack) !== $haystack && mb_strtolower($filterFieldTypeName) === 'double';
    }

    /**
     * @param mixed $haystack
     * @param string $filterFieldTypeName
     *
     * @return bool
     */
    protected static function isIntegerFilter($haystack, string $filterFieldTypeName): bool
    {
        return is_numeric($haystack) && mb_strtolower($filterFieldTypeName) === 'integer';
    }

    /**
     * @param mixed $haystack
     * @param string $filterFieldTypeName
     *
     * @return bool
     */
    protected static function isArrayFilter($haystack, string $filterFieldTypeName): bool
    {
        return is_array($haystack) && mb_strtolower($filterFieldTypeName) === 'array';
    }

    /**
     * @param mixed $haystack
     * @param string $filterFieldTypeName
     *
     * @return bool
     */
    protected static function isCSVFilter($haystack, string $filterFieldTypeName): bool
    {
        return is_string($haystack) && mb_strtolower($filterFieldTypeName) === 'csv';
    }

    /**
     * @param mixed $haystack
     * @param string $filterFieldTypeName
     *
     * @return bool
     */
    protected static function isTimeFilter($haystack, string $filterFieldTypeName): bool
    {
        if (!is_string($haystack)) {
            return false;
        }

        return mb_strtolower($filterFieldTypeName) === 'time' && str_starts_with(mb_strtolower($haystack), 'time')
            || mb_strtolower($filterFieldTypeName) === 'timestamp' && str_starts_with(mb_strtolower($haystack), 'timestamp');
    }

    /**
     * @param mixed $haystack
     * @param string $needle
     *
     * @return string
     */
    protected static function getTimeFilterValue($haystack, string $needle): string
    {
        return trim(last(explode(sprintf('%s:', $needle), $haystack)));
    }

    /**
     * @param mixed $value
     * @param string $filterType
     *
     * @return string
     */
    protected static function setTimeFilterValue($value, string $filterType): string
    {
        return sprintf('%s: %s', $filterType, $value);
    }

    /**
     * @param mixed $value
     * @param FilterType $type
     *
     * @return mixed
     */
    protected static function formatFilterValue($value, FilterType $type)
    {
        switch (mb_strtolower($type->key)) {
            case 'integer':
                $value = (int) $value;
                break;
            case 'double':
                $value = floatval($value);
                break;
            case 'boolean':
                $value = mb_strtolower($value) === 'true';
                break;
            case 'array':
                $value = array_map('trim', explode(',', $value));
                break;
            case 'csv':
                $value = implode(',', array_map('trim', explode(',', $value)));
                break;
            case 'NULL':
                $value = null;
                break;
            case 'time':
            case 'timestamp':
                $value = self::setTimeFilterValue($value, $type->key);
                break;
        }

        return $value;
    }

    /**
     * JSON Filter structure is a single level array of key (filters) and values
     * The advanced filter structure is multi-dimensional and uses filter type, field and operator objects
     *
     * @param array $filters
     *
     * @return bool
     */
    public static function isJSONFilterStructure(array $filters): bool
    {
        return !is_array(Arr::first($filters)) || !Arr::has(Arr::first($filters), ['field', 'type', 'operator']);
    }
}
