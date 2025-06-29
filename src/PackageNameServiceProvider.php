<?php

namespace VendorName\PackageName;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\CachesRoutes;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use VendorName\PackageName\Commands\PackageNameCommand;

class PackageNameServiceProvider extends ServiceProvider
{
    /**
     *  All of the Package event / listener mappings.
     *
     * @var array<class-string, array<class-string>>
     */
    protected array $events = [
        // Events\Event::class => [
        //     Listeners\Listener::class,
        // ],
    ];

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
        $this->registerEvents();
        $this->registerRoutes();
        $this->registerResources();

        if ($this->app->runningInConsole()) {
            $this->registerPublications();
            $this->registerMigrations();
            $this->registerAssets();
            $this->registerCommands();
        }
    }

    /**
     * Register the Package job events.
     */
    private function registerEvents(): void
    {
        $events = $this->app->make(Dispatcher::class);

        foreach ($this->events as $event => $listeners) {
            foreach ($listeners as $listener) {
                $events->listen($event, $listener);
            }
        }
    }

    /**
     * Register the Package routes.
     */
    private function registerRoutes(): void
    {
        if ($this->app instanceof CachesRoutes && $this->app->routesAreCached()) {
            return;
        }

        Route::group([
            'domain' => config('package-name.domain', null),
            'prefix' => config('package-name.path'),
            'namespace' => 'VendorName\PackageName\Http\Controllers',
            'middleware' => config('package-name.middleware', 'web'),
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });

        Route::group([
            'domain' => config('package-name.domain', null),
            'prefix' => Str::finish(config('package-name.path', ''), '/').'api',
            'namespace' => 'VendorName\PackageName\Http\Controllers\Api',
            'middleware' => config('package-name.api_middleware', 'api'),
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        });
    }

    /**
     * Register the Package resources.
     */
    private function registerResources(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../lang', 'package-name');
        // $this->loadJsonTranslationsFrom(__DIR__.'/../lang');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'package-name');
    }

    /**
     * Setup the resource publishing groups for Package.
     */
    private function registerPublications(): void
    {
        $this->publishes([
            __DIR__.'/../config/package-name.php' => $this->app->configPath('package-name.php'),
        ], ['package-name', 'package-name-config']);

        //     $this->publishes([
        //         __DIR__.'/../lang' => $this->app->langPath('vendor/package-name'),
        //     ]);

        //     $this->publishes([
        //         __DIR__.'/../resources/views' => resource_path('views/vendor/package-name'),
        //     ]);
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
        ], [/* 'public', */ 'package-name', 'package-name-assets']);
    }

    /**
     * Register the Package Artisan commands.
     */
    private function registerCommands(): void
    {
        $this->commands([
            PackageNameCommand::class,
        ]);
    }
}
