<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Collection;

class ManyParentMap extends HasMany
{

    public function addEagerConstraints(array $models)
    {
        $whereIn = $this->whereInMethod($this->parent, $this->localKey);
        $this->query->{$whereIn}(
            $this->foreignKey,
            $this->getKeys($models)
        );
    }

    public function match(array $models, Collection $results, $relation)
    {
        foreach ($models as $model) {
            if($model->parent_map)
            {
                $model->setRelation(
                    $relation,
                    clone $results->find($model->parent_map)->values()
                );
            }
        }

        return $models;
    }

    protected function getKeys(array $models, $key = null)
    {
        $parents = collect([]);
        foreach ($models as $key => $value) {
            if ($value->parent_map) {
                $parents = $parents->merge($value->parent_map);
            }
        }
        return $parents->values()->unique(null, true)->sort()->all();
    }
}
