<?php

namespace Maravel\Controllers\API;

use App\Post;
use Maravel\Controllers\APIController;
use App\Requests\Maravel as Request;

class PostController extends APIController
{
    public $order_list = [ 'id', 'title', 'content',
        'summary', 'url', 'slug', 'meta',
        'order', 'type', 'parent'];
    public $clientController = \Maravel\Controllers\Dashboard\UserController::class;
    public $filters = [
        'test' => true
    ];

    public function index(Request $request)
    {
        return $this->_index($request);
    }

    public function show(Request $request, Post $post)
    {
        return $this->_show($request, $post);
    }

    public function create(Request $request)
    {
        return $this->_create($request);
    }

    public function store(Request $request)
    {
        return $this->_store($request);
    }

    public function edit(Request $request, Post $post)
    {
        return $this->_edit($request, $post);
    }

    public function update(Request $request, Post $post)
    {
        return $this->_update($request, $post);
    }

    public function destroy(Request $request, Post $post)
    {
        return $this->_destroy($request, $post);
    }

    public function rules(Request $request, $action)
    {
        switch ($action) {
            case 'update':
            case 'store':
                $rules = [
                    'title' => 'required',
                    'content' => 'required',
                    'summary' => 'nullable|max:400',
                    'slug' => 'require',
                ];
                return $rules;
                break;
            default:
                return [];
                break;
        }
    }

    public function filters($request, $model, $parent = null)
    {

        $filters = [
            [
                'status' => config('guardio.status', ['awaiting', 'active', 'disable']),
            ]
        ];
        $current = [];
        if(in_array($request->status, $filters[0]['status']))
        {
            $model->where('status', $request->status);
            $current['status'] = $request->status;
        }
        return [$filters, $current];
    }
}
