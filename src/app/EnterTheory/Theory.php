<?php
namespace App\EnterTheory;

use App\EnterTheory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Closure;
use Exception;
abstract class Theory
{
    protected $model, $trigger;
    public function __construct($model)
    {
        $this->model = $model;
    }

    abstract public function register(Request $request, EnterTheory $model, array $parameters = []);
    abstract public function rules(Request $request);
    public function run(Request $request)
    {
        if(method_exists($this, 'boot'))
        {
            $this->boot($request) === false ? false : true;
        }
        $trigger = $this->model->trigger;
        if ($trigger) {
            $register = $trigger->register($request, $this->model, $this->trigger ? call_user_func($this->trigger) : []);
            if(!$register instanceof EnterTheory)
            {
                throw new Exception("Register return must be EnterTheory", 1);
            }
            $trigger->commit(function() use ($register){
                return $register;
            });
            return $trigger;
        } else {
            return $this->pass($request);
        }
    }

    public function create(Request $request, $theory, array $parameters = [])
    {
        $theory = $this->load($theory);
        $register = $theory->register($request, $this->model, $parameters);
        $theory->commit(function () use ($register) {
            return $register;
        });
        return $theory;
    }

    public function load($theory)
    {
        if (!($plan = config('auth.theories.' . $theory . '.model'))) {
            throw new Exception("$theory Theory not found!");
        }
        return new $plan($this->model);
    }

    public function pass($request)
    {
        $model = $this;
        if($this->model->parent)
        {
            $model = $this->model->parent->theory;
            $this->passed($request);
            $passed = $model->passed($request);

            $model->commit(function() use ($passed){
                return $passed;
            });
        }
        else
        {
            $passed = $this->passed($request);
            $this->commit(function () use ($passed) {
                return $passed;
            });
        }
        if ($passed instanceof Theory) {
            $model = $passed;
        }
        return $model;
    }

    public function response()
    {
        $commit = $this->commit ? call_user_func($this->commit) : [];
        return $this->toArray($commit);
    }

    protected function toArray($commit)
    {
        if (is_array($commit)) {
            return $commit;
        } elseif (gettype($commit) == 'object' && method_exists($commit, 'toArray')) {
            if($commit instanceof EnterTheory)
            {
                return $commit->toArray();
            }
            return ['data' => $commit->toArray()];
        }
        return $commit;
    }

    protected function trigger(Closure $trigger)
    {
        $this->trigger = $trigger;
    }
    public function commit(Closure $commit)
    {
        $this->commit = $commit;
    }
}
