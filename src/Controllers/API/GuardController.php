<?php

namespace Maravel\Controllers\API;

use Maravel\Controllers\APIController;
use App\Requests\Maravel as Request;
use App\Guard;

class GuardController extends APIController
{
    public $clientController = \Maravel\Controllers\Dashboard\GuardController::class;

    public function index(Request $request)
    {
        return $this->_index($request);
    }

    public function show(Request $request, Guard $guard)
    {
        return $this->_show($request, $guard);
    }

    public function store(Request $request)
    {
        return $this->_store($request);
    }

    public function update(Request $request, Guard $guard)
    {
        return $this->_update($request, $guard);
    }

    public function destroy(Request $request, Guard $guard)
    {
        return $this->_destroy($request, $guard);
    }

    public function rules(Request $request, $action)
    {
        switch ($action) {
            case 'update':
            case 'store':
                $rules = [
                    'title' => 'required|min:3'
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
        $filters = null;
        $current = [];
        return [$filters, $current];
    }
}
