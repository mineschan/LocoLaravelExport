<?php

namespace mineschan\LocoLaravelExport;

use Illuminate\Support\ServiceProvider;

class LocoLaravelExportServiceProvider extends ServiceProvider
{
    /**
     * The console commands.
     *
     * @var array
     */
    protected $commands = [
        'mineschan\LocoLaravelExport\Commands\Export'
    ];

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__ . '/../config/loco-laravel-export.php' => config_path('loco-laravel-export.php'),
        ]);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/loco-laravel-export.php', 'loco-laravel-export');

        $this->commands($this->commands);
    }
}
