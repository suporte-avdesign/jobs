<?php

namespace Modules\Etq\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class EtqServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(module_path('Etq', 'Database/Migrations'));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path('Etq', 'Config/config.php') => config_path('etq.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('Etq', 'Config/config.php'), 'etq'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/etq');

        $sourcePath = module_path('Etq', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/etq';
        }, \Config::get('view.paths')), [$sourcePath]), 'etq');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/etq');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'etq');
        } else {
            $this->loadTranslationsFrom(module_path('Etq', 'Resources/lang'), 'etq');
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production') && $this->app->runningInConsole()) {
            app(Factory::class)->load(module_path('Etq', 'Database/factories'));
        }
    }

    private function registerCommands()
    {
        $this->commands([
            Modules\Etq\Console\EtqTaskEstoqueCommand::class
        ]);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
