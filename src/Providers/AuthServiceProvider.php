<?php

namespace Maravel\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Config;
use Laravel\Passport\Passport;
use App\Token;
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
        Config::set([
            'auth.guards.apiIf' => Config::get('auth.guards.api')
        ]);

        // Set Passport route
        Passport::routes();
        Passport::withoutCookieSerialization();
        // Set expire date
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));

        Passport::useTokenModel(Token::class);

        Gate::define('guardio', function ($user, $request, $guardio, ...$args) {
            array_unshift($args, $request);
            return Gate::allows($guardio, $args);
        });
        Gate::resource('dashboard.users', 'App\Policies\UserPolicy');
        Gate::resource('api.terms', 'App\Policies\TermPolicy');
        Gate::resource('api.users', 'App\Policies\UserPolicy');
        Gate::define('dashboard.view', 'App\Policies\DashboardPolicy@view');

        Gate::define('api.login.as', 'App\Policies\UserPolicy@isAdmin');
    }
}
