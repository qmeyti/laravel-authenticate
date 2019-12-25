<?php

namespace Qmeyti\LaravelAuth;

use Illuminate\Support\ServiceProvider;
use Qmeyti\LaravelAuth\Middleware\QlAuthCheckMiddleware;

class QmeytiLaravelAuthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Publish files
         */
        $this->publishes([
            __DIR__ . '/Config/qlauth.php' => config_path('qlauth.php'),

            __DIR__ . '/Assets/js' => public_path('assets/js'),
            __DIR__ . '/Assets/css' => public_path('assets/css'),

            __DIR__ . '/Views' => resource_path('views/qlauth'),

            __DIR__ . '/Lang' => resource_path('lang'),

            __DIR__ .'/Controllers/QAuth' => app_path('Http/Controllers/QAuth'),
        ]);

        $this->loadMigrationsFrom(__DIR__ . '/Migrations');

        /**
         * Load routes
         */
        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');

        /**
         * Load views
         */
        $this->loadViewsFrom(__DIR__ . '/views', 'qlauth');

        /**
         * Load translations files
         */
        $this->loadTranslationsFrom(__DIR__ . '/Lang', 'qmauth');

    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        /**
         * Register configs
         */
        $this->mergeConfigFrom(
            __DIR__ . '/Config/qlauth.php', 'qlauth'
        );

        /**
         * Add check auth login check middleware
         */
        $this->app['router']->aliasMiddleware('qlauth', QlAuthCheckMiddleware::class);
    }
}
