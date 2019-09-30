<?php

namespace Maravel\Controllers\Dashboard;

use App\Post;
use Maravel\Controllers\WebController;
use App\Requests\Maravel as Request;
use Maravel\Controllers\API\UserController as API;

class PostController extends WebController
{
    public $endpoint = API::class;
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

    public function edit(Request $request, Post $post)
    {
        return $this->_edit($request, $post);
    }
}
