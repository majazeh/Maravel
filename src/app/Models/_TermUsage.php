<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Term;
use DB;
class _TermUsage extends Model
{
    public $guarded = ['id'];

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $term = $model->term()->without('creator', 'parents')->first();
            if($term->parent_id)
            {
                $parents = static::whereIn('term_id', $term->parent_map)
                ->where('table_name', $model->table_name)
                ->where('table_id', $model->table_id)->get('term_id')->pluck('term_id')->toArray();
                $new_parents = [];
                foreach ($term->parent_map as $parent_id) {
                    if (in_array($parent_id, $parents)) continue;
                        $new_parents[] = [
                        'term_id' => $parent_id,
                        'table_name' => $model->table_name,
                        'table_id' => $model->table_id,
                        'updated_at' => $model->updated_at,
                        'created_at' => $model->created_at
                        ];
                }
                static::insertOrIgnore($new_parents);
            }
        });
    }

    public function term()
    {
        return $this->hasOne(Term::class, 'id', 'term_id');
    }

    public static function replace($model, $terms)
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

    public static function replace_map($model, $old_id, $new_id)
    {
        if($old_id ==$new_id) return;
        $table_name = $model->getTable();
        $table_id = $model->id;
        $terms = Term::select('terms.*')
        ->join('term_usages', function($q) use ($table_name, $table_id)
        {
            $q->on('term_usages.term_id', 'terms.id')
            ->where('term_usages.table_name', $table_name)
            ->where('term_usages.table_id', $table_id);
        })->get();
        $term_ids = join(':', $terms->pluck('parent_map')->toArray());
        $term_ids .= ':' . $new_id;
        $new_parens = Term::find($new_id);
        $term_ids .= $new_parens ? ':' . $new_parens->parent_map : '';

        $old_parens = Term::find($old_id);
        $old_ids = $old_id . ':' . ($old_parens ? $old_parens->parent_map : '');
        $old_ids = explode(':', $old_ids);
        $old_ids = array_unique($old_ids);

        $term_ids = explode(':', $term_ids);
        $term_ids = array_unique($term_ids);

        $true_ids = [];
        foreach ($term_ids as $term) {
            if(!array_search($term, $old_ids))
            {
                $true_ids[] = (int) $term;
            }
        }
        $all = static::where([
            'table_name' => $table_name,
            'table_id' => $table_id,
        ])->whereNotIn('term_id', $true_ids)->delete();
        $usages = [];
        foreach ($true_ids as $term) {
            $find = static::where([
                'term_id' => $term,
                'table_name' => $table_name,
                'table_id' => $table_id,
            ])->count();
            if(!$find)
            {
                $usages[] = static::create([
                    'term_id' => $term,
                    'table_name' => $table_name,
                    'table_id' => $table_id,
                ]);
            }
            else
            {
                $usages[] = $find;
            }
        }
        return new Collection($usages);
    }

    public static function add($model, $term_ids)
    {
        $term_ids = is_array($term_ids) ? array_unique($term_ids) : [$term_ids];
        $terms = Term::whereIn('id', $term_ids)->get();
        $terms = $terms->pluck('parent_map')->join(':') .':' . join(':', $term_ids);
        $terms = explode(':', $terms);

        $table_name = $model->getTable();
        $table_id = $model->id;

        $new = [];
        foreach ($terms as $key => $value) {
            if(!$value) continue;
            $find = static::where([
                'term_id' => $value,
                'table_name' => $table_name,
                'table_id' => $table_id,
            ])->count();
            if (!$find) {
                $usages[] = static::create([
                    'term_id' => $value,
                    'table_name' => $table_name,
                    'table_id' => $table_id,
                ]);
            } else {
                $usages[] = $find;
            }
        }
        return new Collection($new);
    }
}
