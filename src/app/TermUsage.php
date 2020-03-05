<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class TermUsage extends Model
{
    public $guarded = ['id'];
    public static function replace($model, $terms, $type, $cat = null)
    {
        $table_name = $model->getTable();
        $terms = is_array($terms) ? array_unique($terms) : [$terms];
        $new_terms = $terms;
        $oldTerms = static::select('term_usages.*')
        ->where('term_usages.table_name', $table_name)
        ->where('term_usages.table_id', $model->id);

        $oldTerms->join('terms', function($q) use ($type, $cat){
            $q->on('terms.id', 'term_usages.term_id')
            ->where('terms.type', $type);
            if($cat)
            {
                $q->where('terms.cat', $cat);
            }
        });

        $oldTerms = $oldTerms->get();
        $deleted = clone $oldTerms;
        $_oldTerms = clone $oldTerms;
        foreach ($oldTerms as $key => $value) {
            $index = array_search($value->term_id, $new_terms);
            if($index !== false)
            {
                $oldTerms->forget($key);
                unset($new_terms[$index]);
            }
            else
            {
                $deleted->forget($key);
            }
        }
        if($oldTerms->count()){
            static::whereIn('in', $oldTerms->pluck('id')->toArray())->delete();
        }
        $new = [];
        foreach ($new_terms as $key => $value) {
            $instanse = (new static)->newInstance([
                'term_id' => $value,
                'table_name' => $table_name,
                'table_id' => $model->id
            ]);
            $instanse->updateTimestamps();
            $new[] = $instanse;
            $new_terms[$key] = $instanse->toArray();
        }
        $new = new Collection($new);
        static::insertOrIgnore($new_terms);
        return [Term::whereIn('id', $terms)->get(), $_oldTerms, $new, $oldTerms];
    }
}
