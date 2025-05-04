<?php

namespace Vendor\PackageName;

use Illuminate\Support\ServiceProvider;
use Vendor\PackageName\Commands\PackageNameCommand;

class PackageNameServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/package-name.php', 'package-name');

        // $this->app->singleton(PackageName::class, fn () => new PackageName);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        // $this->loadTranslationsFrom(__DIR__.'/../lang', 'package-name');
        // $this->loadJsonTranslationsFrom(__DIR__.'/../lang');
        // $this->publishes([
        //     __DIR__.'/../lang' => $this->app->langPath('vendor/package-name'),
        // ]);

        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'package-name');
        // $this->publishes([
        //     __DIR__.'/../resources/views' => resource_path('views/vendor/package-name'),
        // ]);

        if ($this->app->runningInConsole()) {
            $this->registerPublications();
            $this->registerMigrations();
            $this->registerAssets();
            $this->registerCommands();
        }
    }

    private function registerPublications(): void
    {
        $this->publishes([
            __DIR__.'/../config/package-name.php' => $this->app->configPath('package-name.php'),
        ], ['package-name', 'package-name-config']);
    }

    private function registerMigrations(): void
    {
        $method = method_exists($this, 'publishesMigrations') ? 'publishesMigrations' : 'publishes';

        $this->{$method}([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], ['package-name', 'package-name-migrations']);
    }

    private function registerAssets(): void
    {
        $this->publishes([
            __DIR__.'/../dist' => public_path('vendor/package-name'),
        ], 'public');
    }

    private function registerCommands(): void
    {
        $this->commands([
            PackageNameCommand::class,
        ]);
    }
}
