<?php

namespace Maravel\Controllers\API;

use Maravel\Controllers\APIController;
use App\Requests\Maravel as Request;
use App\Guard;
use App\GuardPosition;
use App\Guardio;
class GuardPositionController extends APIController
{
    public $clientController = \Maravel\Controllers\Dashboard\GuardPositionController::class;
    public $parentModel = GuardController::class;

    public function index(Request $request, Guard $guard)
    {
        return $this->_index($request, $guard);
    }

    public function store(Request $request, Guard $guard)
    {
        return $this->_store($request, $guard);
    }

    public function update(Request $request, Guard $guard, $gate)
    {
        return $this->_update($request, $guard);
    }

    public function destroy(Request $request, Guard $guard, $gate)
    {
        $position = GuardPosition::where(['guard_id' => $guard->id, 'gate' => $gate])->first();
        return $this->_destroy($request, $guard, $position);
    }

    public function rules(Request $request, $action, Guard $guard)
    {
        switch ($action) {
            case 'update':
                return [];
            case 'store':
                $rules = [
                    'gate' => 'required',
                    'guard_id' => 'required'
                ];
                return $rules;
                break;
            default:
                return [];
                break;
        }
    }

    public function requestData(Request $request, $action, &$data, Guard $guard)
    {
        if(in_array($action, ['store']) && isset($data['gate']))
        {
            $data['gate'] = in_array($data['gate'], Guardio::gates()) ? $data['gate'] : null;
            $data['guard_id'] = $guard->id;
            if($request->webAccess())
            {
                $data['redirect'] = route('dashboard.guards.positions.index', $guard->serial);
            }
        }
    }

    public function filters($request, $model, $parent = null)
    {
        $filters = null;
        $current = [];
        return [$filters, $current];
    }

    public function queryIndex(Request $request, Guard $guard)
    {
        $positions = $guard->positions;
        foreach (Guardio::gates() as $key => $value) {
            if(!$positions->where('gate', $value)->first())
            {
                $item = new \stdClass;
                $item->gate = $value;
                $item->serial = null;
                $item->value = null;
                $item->description = null;
                $positions->add($item);
            }
        }
        return [$guard, $guard->positions];
    }
}
