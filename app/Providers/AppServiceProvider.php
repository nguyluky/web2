<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Dedoc\Scramble\Scramble;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Scramble::ignoreDefaultRoutes();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Scramble::configure()
            ->expose(
                ui: fn (Router $router, $action) => $router
                    ->get('docs/v1/api', $action),
                document: fn (Router $router, $action) => $router
                    ->get('docs/v1/openapi.json', $action),
            );
    }
}
