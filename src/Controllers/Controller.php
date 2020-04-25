<?php

namespace Maravel\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected $fillable = [];
    public static $result;
    public $statusMessage = ':)';
    public function __construct(Request $request)
    {
        // if(!$request->route()) return;
        $class_name = $this->class_name(null, null, 1);
        if(!isset($this->model))
        {
            $this->model = '\\App\\'.$class_name;
        }
        if(!isset($this->resourceClass))
        {
            $this->resourceClass = '\\App\\Http\\Resources\\'.$class_name;
            if(!class_exists($this->resourceClass))
            {
                $this->resourceClass = \Illuminate\Http\Resources\Json\JsonResource::class;
            }
        }
        if (!isset($this->resourceCollectionClass)) {
            $this->resourceCollectionClass = '\\App\\Http\\Resources\\' . $this->class_name(null, true, 1);
            if (!class_exists($this->resourceCollectionClass)) {
                $this->resourceCollectionClass = null;
            }
        }

        if (!isset($this->parentResourceCollectionClass)) {
            if(!isset($this->parentModel))
            {
                $this->parentResourceCollectionClass = \Illuminate\Http\Resources\Json\JsonResource::class;
            }
            else
            {
                $this->parentResourceCollectionClass = '\\App\\Http\\Resources\\' . $this->class_name($this->parentModel, null, 2);
                if (!class_exists($this->parentResourceCollectionClass)) {
                    $this->parentResourceCollectionClass = \Illuminate\Http\Resources\Json\JsonResource::class;
                }
            }
        }
    }

    /**
     * 0 : unchange
     * 1 : first upper
     * 2 : lower
     * 3 : upper
     */
    public function class_name($class_name = null, $plural = false, $lower = 0)
    {
        $namespace = explode('\\', $class_name ?: (isset($this->alias) ? $this->alias : get_class($this)));
        $class_name = substr(end($namespace), -10, 10) == 'Controller' ? substr(end($namespace), 0, -10) : end($namespace);
        $class_name = $plural ? Str::plural($class_name) : $class_name;
        switch ($lower) {
            case 1: return ucfirst($class_name);
            case 2: return strtolower($class_name);
            case 3: return strtoupper($class_name);
            default: return $class_name;
        }
    }

    public function findOrFail($id, $model = null)
    {
        if (!$model) {
            $model = $this->model;
        }
        if (gettype($id) !== 'object') {
            $query = new $model;
            $model = $query->resolveRouteBinding($id);
            if (!$model) {
                $name = explode('\\', $model);
                $name = end($name);
                throw (new ModelNotFoundException)->setModel(trim($model, '\\'), $id);
            }
            return $model;
        } else {
            return $id;
        }
    }

    public function setFillable($action, $parameters)
    {
        $this->fillable[$action] = $parameters;
    }

    public function fillable($action)
    {
        return isset($this->fillable[$action]) ? $this->fillable[$action] : null;
    }

    public function fail($model = null, $id = null)
    {
        if (!$model) {
            $model = $this->model;
        }
        throw (new ModelNotFoundException)->setModel(trim($model, '\\'), $id);
    }

    public function findArgs($request, $arg1 = null, $arg2 = null)
    {
        if ($arg2) {
            $model = $this->findOrFail($arg2, $this->model);
            $parent = $this->findOrFail($arg1, $arg1 instanceof Eloquent ? get_class($arg1) : $this->parentModel);
        } else {
            $model = $this->findOrFail($arg1, $this->model);
            $parent = null;
        }
        return [$parent, $model];
    }

    public function __call($method, $parameters)
    {
        if(method_exists($this, '_' . $method))
        {
            return $this->{'_' . $method}(...$parameters);
        }
        parent::__call($method, $parameters);
    }
}
