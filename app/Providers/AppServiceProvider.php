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
        // Vite v5 with Laravel plugin creates manifest at public/build/.vite/manifest.json
        // Configure Laravel to look in the correct location
        if (class_exists(\Illuminate\Foundation\Vite::class)) {
            if (method_exists(\Illuminate\Foundation\Vite::class, 'useManifestFilename')) {
                \Illuminate\Support\Facades\Vite::useManifestFilename('.vite/manifest.json');
            }
        }

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
