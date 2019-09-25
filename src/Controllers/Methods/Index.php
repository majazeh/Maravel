<?php

namespace Maravel\Controllers\Methods;

use Illuminate\Http\Request;

trait Index
{
    public function _index(Request $request)
    {
        list($parent, $model, $order_list, $current_order, $default_order, $filters, $current_filter) = $this->queryIndex(...func_get_args());
        $result = new $this->resourceCollectionClass($model);
        $result->additional([
            'meta' => [
                'orders' => [
                    'allowed' => $order_list,
                    'current' => $current_order,
                    'default' => $default_order,
                ],
                'filters' => [
                    'allowed' => $filters,
                    'current' => $current_filter,
                ]
            ]
        ]);
        return $result;
    }

    public function queryIndex($request, $parent = null)
    {
        if ($parent) {
            $model = $this->model::select('*');
            $parent = $this->findOrFail($parent, $this->parentModel);
        } else {
            $model = $this->model::select('*');
            $parent = null;
        }
        list($filters, $current_filter) = [null, null];
        if (method_exists($this, 'filters')) {
            list($filters, $current_filter) = $this->filters($request, $model, $parent = null);
        }
        list($model, $order_list, $current_order, $default_order) = $this->paginate($request, $model, $parent = null);
        if($current_filter)
        {
            $model->appends($request->all(...array_keys($current_filter)));
        }
        return [$parent, $model, $order_list, $current_order, $default_order, $filters, $current_filter];
    }

    public function paginate($request, $model, $parent = null, $order_list = [], $default = [])
    {
        $order_list = $order_list ?: (isset($this->order_list) ? $this->order_list : ['id']);
        $default = $default ?: (isset($this->order_default) ? $this->order_default : ['id', 'desc']);
        $keys = array_keys($order_list);
        $order_string = $request->order && in_array($request->order, $keys) ? strtolower($request->order) : $default[0];
        $orders = explode(',', $order_string);

        $sort_string = $request->sort ?: $default[1];
        $sorts = explode(',', $sort_string);
        $current = [];
        foreach ($orders as $key => $order) {
            if (isset($order_list[$order]) || in_array($order, $order_list)) {
                $order = isset($order_list[$order]) ? $order_list[$order] : $order;
                $sort = isset($sorts[$key]) && in_array(strtolower($sorts[$key]), ['asc', 'desc']) ? strtolower($sorts[$key]) : 'desc';
                $model->orderBy($order, $sort);
                $current[$order] = $sort;
            }
        }
        $paginate = $model->paginate();
        if ($order_string != $default[0] || $sort_string != $default[1]) {
            $paginate->appends($request->all('order', 'sort'));
        }
        return [$paginate, $order_list, $current, $default];
    }
}
