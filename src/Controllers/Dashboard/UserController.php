<?php

namespace Maravel\Controllers\Dashboard;

use Maravel\Controllers\Controller as BaseController;
use Maravel\Controllers\Methods;
use Maravel\Requests\User as Request;
use App\User;
class UserController extends BaseController
{
    public $order_list = ['id', 'name', 'username', 'status', 'type', 'gender'];

    public $filters = [
        'test' => true
    ];

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function index(Request $request)
    {
        return $this->_index($request);
    }

    public function show(Request $request, User $user)
    {
        return $this->_show($request, $user);
    }

    public function create(Request $request)
    {
        return $this->_create($request);
    }

    public function store(Request $request)
    {
        return $this->_store($request);
    }

    public function edit(Request $request, User $user)
    {
        return $this->_edit($request);
    }

    public function update(Request $request, User $user)
    {
        return $this->_update($request);
    }

    public function destroy(Request $request, User $user)
    {
        return $this->_destroy($request);
    }
}
