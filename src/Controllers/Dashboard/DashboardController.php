<?php

namespace Maravel\Controllers\Dashboard;

use Maravel\Controllers\WebController;
use App\Requests\Maravel as Request;
use Illuminate\Http\Request as DRequest;

class DashboardController extends WebController
{
    public $resource = 'dashboard';
    public $views = [
        'dashboard' => 'dashboard.home'
    ];
    public function index(Request $request)
    {
        return $this->view($request);
    }

    public function rules(DRequest $request, $action)
    {
        return [];
    }
}
