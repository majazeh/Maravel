<?php

namespace App\Http\Controllers\API;

use App\Requests\Maravel as Request;
use App\Term;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class _TermController extends _Controller
{
    public function index(Request $request)
    {
        return $this->_index($request);
    }

    public function store(Request $request)
    {
        return $this->_store($request, function($request){
            return $this->model::create($request->all('title', 'creator_id', 'parent_id'));
        });
    }

    public function show(Request $request, Term $term)
    {
        return $this->_show($request, $term);
    }

    public function find(Request $request)
    {
        $parent_id = $request->parent_id ?: null;
        $term = $this->model::where('title', $request->title);
        if($parent_id)
        {
            $term->where('parent_id', $parent_id);
        }
        else
        {
            $term->whereNull('parent_id');
        }
        if($term = $term->first())
        {
            return $this->show($request, $term);
        }
        throw (new ModelNotFoundException)->setModel('App\User', $request->title);

    }

    public function update(Request $request, Term $term)
    {
    }

    public function destroy(Request $request, Term $term)
    {
    }

    public function rules(Request $request, $action, $term = null)
    {
        switch ($action) {
            case 'store':
                return [
                    'title' => [
                        'required',
                        'string',
                        'min:3',
                        'max:60',
                        $request->parent_id
                        ? Rule::unique('terms', 'title')->where('parent_id', $request->parent_id)
                        : Rule::unique('terms', 'title')->whereNull('parent_id')
                    ],
                    'parent_id' => [
                        'nullable',
                        'exists_serial:terms,id'. ($request->parent_id ? ',creator_id,'. (auth()->user()->isAdmin() ? 1 : auth()->id()) : ''),
                        function($key, $value, $fail){
                            $parents = count(explode(':', Term::find($value)->parent_map));
                            if($parents > 6)
                            {
                                $fail('parents is full!');
                            }
                        }
                    ],
                    'creator_id' => 'nullable|exists:users,id',
                ];
                break;
            case 'find' :
                return [
                    'parent_id' => [
                        'nullable',
                        'exists_serial:terms,id'
                    ],
                ];
            break;
        }
        return [];
    }

}
