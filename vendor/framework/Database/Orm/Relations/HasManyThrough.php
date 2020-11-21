<?php

namespace MyPlugin\Framework\Database\Orm\Relations;

use MyPlugin\Framework\Database\Orm\Model;
use MyPlugin\Framework\Database\Orm\Builder;
use MyPlugin\Framework\Database\Orm\Collection;
use MyPlugin\Framework\Database\Query\Expression;

class HasManyThrough extends Relation
{

    /**
     * The distance parent model instance.
     *
     * @var \MyPlugin\Framework\Database\Orm\Model
     */
    protected $farParent;

    /**
     * The near key on the relationship.
     *
     * @var string
     */
    protected $firstKey;

    /**
     * The far key on the relationship.
     *
     * @var string
     */
    protected $secondKey;

    /**
     * Create a new has many relationship instance.
     *
     * @param  \MyPlugin\Framework\Database\Orm\Builder  $query
     * @param  \MyPlugin\Framework\Database\Orm\Model  $farParent
     * @param  \MyPlugin\Framework\Database\Orm\Model  $parent
     * @param  string  $firstKey
     * @param  string  $secondKey
     * @return void
     */
    public function __construct(Builder $query, Model $farParent, Model $parent, $firstKey, $secondKey)
    {
        $this->firstKey = $firstKey;
        $this->secondKey = $secondKey;
        $this->farParent = $farParent;

        parent::__construct($query, $parent);
    }

    /**
     * Set the base constraints on the relation query.
     *
     * @return void
     */
    public function addConstraints()
    {
        $parentTable = $this->parent->getTable();

        $this->setJoin();

        if (static::$constraints) {
            $this->query->where($parentTable.'.'.$this->firstKey, '=', $this->farParent->getKey());
        }
    }

    /**
     * Add the constraints for a relationship count query.
     *
     * @param  \MyPlugin\Framework\Database\Orm\Builder  $query
     * @param  \MyPlugin\Framework\Database\Orm\Builder  $parent
     * @return \MyPlugin\Framework\Database\Orm\Builder
     */
    public function getRelationCountQuery(Builder $query, Builder $parent)
    {
        $parentTable = $this->parent->getTable();

        $this->setJoin($query);

        $query->select(new Expression('count(*)'));

        $key = $this->wrap($parentTable.'.'.$this->firstKey);

        return $query->where($this->getHasCompareKey(), '=', new Expression($key));
    }

    /**
     * Set the join clause on the query.
     *
     * @param  \MyPlugin\Framework\Database\Orm\Builder|null  $query
     * @return void
     */
    protected function setJoin(Builder $query = null)
    {
        $query = $query ?: $this->query;

        $foreignKey = $this->related->getTable().'.'.$this->secondKey;

        $query->join($this->parent->getTable(), $this->getQualifiedParentKeyName(), '=', $foreignKey);
    }

    /**
     * Set the constraints for an eager load of the relation.
     *
     * @param  array  $models
     * @return void
     */
    public function addEagerConstraints(array $models)
    {
        $table = $this->parent->getTable();

        $this->query->whereIn($table.'.'.$this->firstKey, $this->getKeys($models));
    }

    /**
     * Initialize the relation on a set of models.
     *
     * @param  array   $models
     * @param  string  $relation
     * @return array
     */
    public function initRelation(array $models, $relation)
    {
        foreach ($models as $model) {
            $model->setRelation($relation, $this->related->newCollection());
        }

        return $models;
    }

    /**
     * Match the eagerly loaded results to their parents.
     *
     * @param  array   $models
     * @param  \MyPlugin\Framework\Database\Orm\Collection  $results
     * @param  string  $relation
     * @return array
     */
    public function match(array $models, Collection $results, $relation)
    {
        $dictionary = $this->buildDictionary($results);

        // Once we have the dictionary we can simply spin through the parent models to
        // link them up with their children using the keyed dictionary to make the
        // matching very convenient and easy work. Then we'll just return them.
        foreach ($models as $model) {
            $key = $model->getKey();

            if (isset($dictionary[$key])) {
                $value = $this->related->newCollection($dictionary[$key]);

                $model->setRelation($relation, $value);
            }
        }

        return $models;
    }

    /**
     * Build model dictionary keyed by the relation's foreign key.
     *
     * @param  \MyPlugin\Framework\Database\Orm\Collection  $results
     * @return array
     */
    protected function buildDictionary(Collection $results)
    {
        $dictionary = array();

        $foreign = $this->firstKey;

        // First we will create a dictionary of models keyed by the foreign key of the
        // relationship as this will allow us to quickly access all of the related
        // models without having to do nested looping which will be quite slow.
        foreach ($results as $result) {
            $dictionary[$result->{$foreign}][] = $result;
        }

        return $dictionary;
    }

    /**
     * Get the results of the relationship.
     *
     * @return mixed
     */
    public function getResults()
    {
        return $this->get();
    }

    /**
     * Execute the query as a "select" statement.
     *
     * @param  array  $columns
     * @return \MyPlugin\Framework\Database\Orm\Collection
     */
    public function get($columns = array('*'))
    {
        // First we'll add the proper select columns onto the query so it is run with
        // the proper columns. Then, we will get the results and hydrate out pivot
        // models with the result of those columns as a separate model relation.
        $select = $this->getSelectColumns($columns);

        $models = $this->query->addSelect($select)->getModels();

        // If we actually found models we will also eager load any relationships that
        // have been specified as needing to be eager loaded. This will solve the
        // n + 1 query problem for the developer and also increase performance.
        if (count($models) > 0) {
            $models = $this->query->eagerLoadRelations($models);
        }

        return $this->related->newCollection($models);
    }

    /**
     * Set the select clause for the relation query.
     *
     * @param  array  $columns
     * @return \MyPlugin\Framework\Database\Orm\Relations\BelongsToMany
     */
    protected function getSelectColumns(array $columns = array('*'))
    {
        if ($columns == array('*')) {
            $columns = array($this->related->getTable().'.*');
        }

        return array_merge($columns, array($this->parent->getTable().'.'.$this->firstKey));
    }

    /**
     * Get a paginator for the "select" statement.
     *
     * @param  int    $perPage
     * @param  array  $columns
     * @return \Illuminate\Pagination\Paginator
     */
    public function paginate($perPage = null, $columns = array('*'))
    {
        $this->query->addSelect($this->getSelectColumns($columns));

        $pager = $this->query->paginate($perPage, $columns);

        return $pager;
    }

    /**
     * Get the key for comparing against the parent key in "has" query.
     *
     * @return string
     */
    public function getHasCompareKey()
    {
        return $this->farParent->getQualifiedKeyName();
    }
}
