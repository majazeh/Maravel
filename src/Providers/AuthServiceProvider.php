<?php

namespace Maravel\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Laravel\Passport\Passport;
use App\Guardio;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];
    public function boot()
    {
        $this->registerPolicies();
        Config::set([
            'auth.guards.api.driver' => 'passport'
        ]);

        // Set Passport route
        Passport::routes();
        Passport::withoutCookieSerialization();
        // Set expire date
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));

        Gate::define('guardio', function ($user, $request, $guardio, ...$args) {
            if (!Guardio::has($guardio)) {
                return true;
            }
            array_unshift($args, $request);
            return Gate::allows($guardio, $args);
        });
        Gate::resource('users', 'App\Policies\UserPolicy');
    }
}
