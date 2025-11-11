<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configure Vite manifest path for Vite v5 (.vite/manifest.json structure)
        \Illuminate\Support\Facades\Vite::useBuildPath('build/.vite');

        // Ensure admin middleware is aliased for all Laravel versions / cache states
        try {
            $router = $this->app->make(\Illuminate\Routing\Router::class);
            $router->aliasMiddleware('admin', \App\Http\Middleware\IsAdmin::class);
            $router->aliasMiddleware('is_admin', \App\Http\Middleware\IsAdmin::class);
        } catch (\Throwable $e) {
            // If router not available yet or aliasing fails, skip silently.
        }
    }
}
