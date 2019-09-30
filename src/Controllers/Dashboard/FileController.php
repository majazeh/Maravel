<?php

namespace Maravel\Controllers\Dashboard;

use Maravel\Controllers\WebController;
use App\Requests\Maravel as Request;
use App\File;
use Maravel\Controllers\API\UserController as API;

class FileController extends WebController
{
    public $endpoint = API::class;
    public function index(Request $request)
    {
        return $this->_index($request);
    }

    public function show(Request $request, File $file)
    {
        return $this->_show($request, $file);
    }

    public function create(Request $request)
    {
        return $this->_create($request);
    }

    public function edit(Request $request, File $file)
    {
        return $this->_edit($request, $file);
    }
}
