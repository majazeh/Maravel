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
        return $this->_store($request);
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
        $this->_update($request, $term);
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
                        'exists_serial:terms,id,creator_id,'. auth()->id(),
                        function($key, $value, $fail){
                            $parent = Term::find($value)->parent_map;
                            $parents = $parent ? count(Term::find($value)->parent_map) : 1;
                            if($parents > Term::MAX_LEVEL)
                            {
                                $fail('Max parents level is '. Term::MAX_LEVEL);
                            }
                        }
                    ],
                    'creator_id' => 'nullable|exists:users,id',
                ];
                break;
            case 'update' :
                return [
                    'title' => [
                        'required',
                        'string',
                        'min:3',
                        'max:60',
                        $request->parent_id
                            ? Rule::unique('terms', 'title')->where('parent_id', $request->parent_id)->ignore($term->id)
                            : Rule::unique('terms', 'title')->whereNull('parent_id')->ignore($term->id)
                        ]
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
    public function requestData($request, $action, &$data)
    {
        if($action == 'store')
        {
            $data['creator_id'] = auth()->id();
        }
    }
}
