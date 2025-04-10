<?php

namespace App\Providers;

use App\Guards\AuthTokenGuard;
use App\Models\Project;
use App\Policies\ProjectPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

// use Spatie\Permission\Models\Permission;
// use Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Project::class => ProjectPolicy::class,
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

        Gate::define('view-projects', function ($user) {
            return $user->hasPermissionTo('view-projects');
        });

        Gate::define('view-active-projects', function ($user) {
            return $user->hasPermissionTo('view-active-projects');
        });

    }
}
