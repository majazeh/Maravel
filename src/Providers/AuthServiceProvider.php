<?php

namespace Maravel\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('guardio', function ($user, $request, $guardio, ...$args) {
            if (!\Auth::guardio($guardio)) {
                return false;
            }
            array_unshift($args, $request);
            return Gate::allows($guardio, $args);
        });
        Gate::resource('users', 'App\Policies\UserPolicy');
    }
}
