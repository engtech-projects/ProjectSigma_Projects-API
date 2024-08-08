<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Guards\AuthTokenGuard;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        $this->app['auth']->extend(
            'hrms-auth',
            function ($app, $name, array $config) {
                $guard = new AuthTokenGuard(
                    $app['request']
                );
                $app->refresh('request', $guard, 'setRequest');
                return $guard;
            }
        );
    }
}
