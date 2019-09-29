<?php

namespace Maravel\Controllers\Dashboard;

use Maravel\Controllers\WebController;
use App\Requests\Maravel as Request;
use App\Guard;
use App\GuardPosition;
use Maravel\Controllers\API\GuardPositionController as API;

class GuardPositionController extends WebController
{
    public $endpoint = API::class;
    public function index(Request $request, Guard $guard)
    {
        return $this->_index($request, $guard);
    }

    public function create(Request $request, Guard $guard)
    {
        return $this->_create($request);
    }

    public function edit(Request $request, Guard $guard, GuardPosition $guardPosition)
    {
        return $this->_edit($request, $guard);
    }
}
