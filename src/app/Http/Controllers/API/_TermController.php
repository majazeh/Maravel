<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\Term as ResourcesTerm;
use App\Requests\Maravel as Request;
use App\Term;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class _TermController extends Controller
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
        throw (new ModelNotFoundException)->setModel('App\Term', $request->title);

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
                        auth()->user()->isAdmin() ? 'exists_serial:terms,id' : 'exists_serial:terms,id,creator_id,'. auth()->id(),
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
    public function filters(Request $request, $model)
    {
        $allowed = [
            'q' => '%s',
            'parent' => '$Term',
        ];
        $current = [];
        if ($request->parent) {
            if ($term = Term::findBySerial($request->parent)) {
                if ($request->nested === '1') {
                    $parents = $term->parents->add(Term::without(['parents'])->find($term->id));
                    $ids = $parents->pluck('id')->toArray();
                    $model->where('parent_map', 'like', join(',', $ids) . '%');
                } else {
                    $model->where('parent_id', $term->id);
                }
                $current['parent'] = new ResourcesTerm($term);
            } else {
                $model->where('parent_id', 0);
                $current['parent'] = null;
            }
        } elseif ($request->parent === '0') {
            $model->whereNull('parent_id');
        }

        if ($request->q) {
            $model->where('title', 'like', '%' . $request->q . '%');
            $current['q'] = $request->parent_id;
        }
        return [$allowed, $current];
    }
}
