<?php

namespace Abdurrahmanriyad\Writeup;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class WriteupServiceProvider extends ServiceProvider
{
    /**
     * @param Router $router
     */
    public function boot(Router $router)
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('writeup.php'),
            ], 'config');
        }


        if (config('writeup.run_in_production')) {
            if (config('writeup.request_log.enable')) {
                $router->pushMiddlewareToGroup('api', WriteupRequestMiddleware::class);
                $router->pushMiddlewareToGroup('web', WriteupRequestMiddleware::class);
            }

            if (config('writeup.response_log.enable')) {
                $router->pushMiddlewareToGroup('api', WriteupResponseMiddleware::class);
                $router->pushMiddlewareToGroup('web', WriteupResponseMiddleware::class);
            }

            if (config('writeup.query_log.enable')) {
                $router->pushMiddlewareToGroup('api', WriteupQueryMiddleware::class);
                $router->pushMiddlewareToGroup('web', WriteupQueryMiddleware::class);
            }
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'writeup');
    }
}
