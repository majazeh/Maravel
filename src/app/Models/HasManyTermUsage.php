<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class HasManyTermUsage extends HasMany
{
    public function createMany(iterable $records = [])
    {
        $records = array_map(function($value){
            return !is_array($value) ? ['term_id' => $value] : $value;
        }, $records);
        $instances = $this->related->newCollection();

        foreach ($records as $record) {
            $instances->push($this->create($record));
        }

        return $instances;
    }
    protected function setForeignAttributesForCreate(Model $model)
    {
        $model->setAttribute($this->getForeignKeyName(), $this->getParentKey());
        $model->setAttribute('table_name', $this->parent->getTable());
    }
}
