<?php
namespace App\EnterTheory;

use App\EnterTheory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Closure;
use Exception;
abstract class Theory
{
    protected $model, $caller, $result;
    public function __construct($model)
    {
        $this->model = $model;
        $this->key = $model->key;
        $this->value = $model->value;
        $this->user_id = $model->user_id;
        $this->result = $model;
    }

    abstract public function register(Request $request, EnterTheory $model, array $parameters = []);
    abstract public function rules(Request $request);
    public function run(Request $request, Theory $caller = null)
    {
        $this->caller = $caller;
        $result = $this->boot($request);
        if($result instanceof Theory && !($result instanceof static))
        {
            return $result;
        }
        elseif($result instanceof EnterTheory)
        {
            return $result->theory;
        }
        elseif($result != $this){
            $this->result = $result;
        }
        return $this;
    }

    public function tryPass(Theory $theory, Request $request)
    {
        $this->caller = $theory;
        $result = $this->pass($request);
        if($result instanceof Theory && $result != $this)
        {
            return $result;
        }
        elseif($this != $result)
        {
            $this->result = $this;
        }
        return $this;
    }

    public function pass(Request $request)
    {
        $result = $this->passed($request) ?: $this->result;
        if($this->model->parent && $this->model->parent->expired_at)
        {
            $this->model->parent->update(['trigger' => null]);
            if($this->model->parent->type == 'chain')
            {
                return $this->model->parent->theory->tryPass($this, $request);
            }
            return $this->model->parent->theory;
        }
        elseif($this->model->parent)
        {
            return $this->model->parent->theory->tryPass($this, $request);
        }
        if($result instanceof Theory && $result != $this)
        {
            return $result;
        }
        $this->result = $result;
        return $this;
    }

    public function create(Request $request, $theory, array $parameters = [])
    {
        return $this->load($theory)->tryRegister($this, $request, $parameters);
    }

    public function load($theory)
    {
        if (!($plan = config('auth.theories.' . $theory . '.model'))) {
            throw new Exception("$theory Theory not found!");
        }
        return new $plan($this->model);
    }

    public function response()
    {
        if (is_array($this->result)) {
            return $this->result;
        } elseif (gettype($this->result) == 'object' && method_exists($this->result, 'toArray')) {
            if ($this->result instanceof EnterTheory) {
                return $this->result->toArray();
            }
            return ['data' => $this->result->toArray()];
        }
        return $this->result;
    }

    public function tryRegister(Theory $theory, Request $request, array $parameters = [])
    {
        $this->caller = $theory;
        $result = $this->result = $this->register($request, $theory->model, $parameters);
        if(isset($result->trigger))
        {
            return $result->trigger->tryRegister($result->theory, $request, $parameters);
        }
        return $this;
    }

    public function trigger(Request $request, array $parameters = [])
    {
        if($this->model->trigger)
        {
            return $this->model->trigger->tryRegister($this, $request, $parameters);
        }
        return $this->pass($request);
    }
}
