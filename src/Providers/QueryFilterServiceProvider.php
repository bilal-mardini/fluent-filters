<?php

namespace BilalMardini\FluentFilters\Providers;

use Illuminate\Support\ServiceProvider;

class QueryFilterServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([
            \BilalMardini\FluentFilters\Console\Commands\PackageInstallCommand::class,
            \BilalMardini\FluentFilters\Console\Commands\MakeQueryFilters::class,
        ]);
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/filters.php' => config_path('filters.php'),
        ]);

        $this->mergeConfigFrom(
            __DIR__.'/../../config/filters.php', 'filters'
        );
    }
}
