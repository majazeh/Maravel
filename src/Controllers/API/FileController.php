<?php

namespace Maravel\Controllers\API;

use App\File;
use App\Post;
use Carbon\Carbon;
use Maravel\Controllers\APIController;
use App\Requests\Maravel as Request;

class FileController extends APIController
{
    public $table = Post::class;
    public $order_list = [ 'id', 'slug', 'group', 'mime', 'exec'];
    public $clientController = \Maravel\Controllers\Dashboard\FileController::class;
    public $filters = [
        'test' => true
    ];

    public function index(Request $request)
    {
        return $this->_index($request);
    }

    public function show(Request $request, File $user)
    {
        return $this->_show($request, $user);
    }

    public function create(Request $request)
    {
        return $this->_create($request);
    }

    public function store(Request $request)
    {
        $request->merge(['type' => 'attachment']);
        return $this->_store($request);
    }

    public function edit(Request $request, File $file)
    {
        return $this->_edit($request, $file);
    }

    public function update(Request $request, File $file)
    {
        return $this->_update($request, $file);
    }

    public function destroy(Request $request, File $file)
    {
        return $this->_destroy($request, $file);
    }

    public function rules(Request $request, $action)
    {
        switch ($action) {
            case 'update':
            case 'store':
                $rules = [
                    'upload' => 'required',
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
