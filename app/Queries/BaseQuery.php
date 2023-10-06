<?php

namespace App\Queries;

use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\QueryBuilder;

abstract class BaseQuery
{
    /**
     * The query builder instance.
     */
    public $builder;

    /**
     * The model for this query.
     */
    public $model;

    /**
     * Instantiate a new BaseQuery instance.
     */
    public function __construct()
    {
        $this->builder = $this->builder();
    }

    /**
     * Provide the underlying query builder instance.
     *
     * @return \Spatie\QueryBuilder\QueryBuilder
     */
    public function builder(): QueryBuilder
    {
        return QueryBuilder::for($this->model);
    }

    /**
     * Scope the query to those related to the model.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return \Spatie\QueryBuilder\QueryBuilder
     */
    public function whereBelongsTo(Model $model): QueryBuilder
    {
        return $this->builder->where($model->getForeignKey(), $model->getKey());
    }

    /**
     * Scope the query to a model instance.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return \Spatie\QueryBuilder\QueryBuilder
     */
    public function whereKey(Model $model): QueryBuilder
    {
        return $this->builder->where($model->getKeyName(), $model->getKey());
    }

    /**
     * Call methods on the underlying query instance.
     *
     * @param  string  $method
     * @param  array  $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        return $this->builder->$method(...$args);
    }
}
