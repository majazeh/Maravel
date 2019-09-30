<?php

namespace Maravel\Controllers\API;

use App\File;
use App\Post;
use Carbon\Carbon;
use Maravel\Controllers\APIController;
use App\Requests\Maravel as Request;

class AttachmentController extends APIController
{
    public $model = Post::class;

    public $clientController = \Maravel\Controllers\Dashboard\AttachmentController::class;

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
        \DB::beginTransaction();
        $request->request->remove('file');
        $post_record = $this->_store($request);
        $slug = "/attachments/$post_record->serial". ($post_record->title ? '_'. $post_record->title : '');
        $post_record->resource->slug = $slug;
        $post_record->resource->url = asset($slug);
        $post_record->resource->save();
        $file = File::move($post_record->resource, $request->file('file'));
        \DB::commit();
        return new $this->resourceClass(Post::find($post_record->id));
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
                    'file' => 'required',
                    'status' => 'nullable|in:draft,publish,disable',
                    'title' => 'nullable',
                    'content' => 'nullable',
                ];
                return $rules;
                break;
            default:
                return [];
                break;
        }
    }

    public function fields(Request $request, $action)
    {
        $data = [
            'status' => $request->status ?: '',
            'title' => $request->title,
            'content' => $request->content,
        ];
        if($action == 'store')
        {
            $data['type'] = 'attachment';
            $data['creator_id'] = auth()->id();
        }
        return $data;
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
