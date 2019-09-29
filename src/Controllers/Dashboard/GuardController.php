<?php

namespace Maravel\Controllers\Dashboard;

use Maravel\Controllers\WebController;
use App\Requests\Maravel as Request;
use App\Guard;
use Maravel\Controllers\API\GuardController as API;

class GuardController extends WebController
{
    public $endpoint = API::class;
    public function index(Request $request)
    {
        return $this->_index($request);
    }

    public function show(Request $request, Guard $guard)
    {
        return $this->_show($request, $guard);
    }

    public function create(Request $request)
    {
        return $this->_create($request);
    }

    public function edit(Request $request, Guard $guard)
    {
        return $this->_edit($request, $guard);
    }
}
