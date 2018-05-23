<?php

namespace Makeable\LaravelInvoicing\Providers;

use Illuminate\Support\ServiceProvider;

class InvoicingServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (! class_exists('CreateInvoicingTables')) {
            $this->publishes([
                __DIR__.'/../../database/migrations/create_invoicing_tables.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_invoicing_tables.php'),
            ], 'migrations');
        }

        $this->mergeConfigFrom(__DIR__.'/../../config/laravel-invoicing.php', 'laravel-invoicing');
        $this->publishes([__DIR__.'/../../config/laravel-invoicing.php' => config_path('laravel-invoicing.php')], 'config');
    }

    public function register()
    {
    }
}
