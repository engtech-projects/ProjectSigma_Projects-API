<?php

namespace App\Providers;

use App\Guards\AuthTokenGuard;
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

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
        User::class => UserPolicy::class,
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
        Scramble::configure()
            ->withDocumentTransformers(function (OpenApi $openApi) {
                $openApi->secure(
                    SecurityScheme::http('bearer')
                );
            });
    }
}
