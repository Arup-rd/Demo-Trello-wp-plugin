<?php

namespace MyPlugin\Framework\Database\Orm;

interface ScopeInterface
{

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \MyPlugin\Framework\Database\Orm\Builder  $builder
     * @return void
     */
    public function apply(Builder $builder);

    /**
     * Remove the scope from the given Eloquent query builder.
     *
     * @param  \MyPlugin\Framework\Database\Orm\Builder  $builder
     * @return void
     */
    public function remove(Builder $builder);
}
