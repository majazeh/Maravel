<?php
namespace App\EnterTheory;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\EnterTheory;
use App\User;

class Fake extends Theory
{
    public function __construct($model = null)
    {
        parent::__construct($model ?: new EnterTheory);
    }

    public function register(Request $request, EnterTheory $model = null, array $parameters = [])
    {

    }

    public function rules(Request $request)
    {
    }
}
