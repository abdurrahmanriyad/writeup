<?php

namespace Abdurrahmanriyad\Writeup;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class WriteupServiceProvider extends ServiceProvider
{


    /**
     * check if writeup is enabled for logging
     * @return bool
     */

    protected function isWriteupEnabled()
    {
        $writeup_enabled = config('writeup.run_in_production');
        $app_environment_name = config('app.env');

        if (
            $app_environment_name === "production" && $writeup_enabled ||
            $app_environment_name && $writeup_enabled
        ) {
            return true;
        }

        return false;
    }
    
    /**
     * check if writeup is enabled
     * @return bool
     */
    protected function isEnabled()
    {
        $enabled = false;
        $config = $this->app['config'];

        if ($config->get('writeup.request_log.enable')) {
            $enabled = true;
        }

        if ($enabled && $this->app->environment('production') && !$config->get('writeup.run_in_production')) {
            $enabled = false;
        }

        return $enabled;
    }
    
    /**
     * @param Router $router
     */
    public function boot(Router $router)
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('writeup.php'),
            ], 'writeup');
        }

        if ($this->isWriteupEnabled()) {
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
