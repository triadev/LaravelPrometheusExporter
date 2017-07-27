<?php
namespace Triadev\PrometheusExporter\Provider;

use Illuminate\Support\ServiceProvider;
use Triadev\PrometheusExporter\Contract\PrometheusExporterContract;
use Triadev\PrometheusExporter\PrometheusExporter;

/**
 * Class PrometheusExporterServiceProvider
 *
 * @author Christopher Lorke <christopher.lorke@gmx.de>
 * @package Triadev\PrometheusExporter\Provider
 */
class PrometheusExporterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $source = realpath(__DIR__ . '/../Config/config.php');

        $this->publishes([
            __DIR__ . '/../Config/config.php' => config_path('prometheus-exporter.php'),
        ], 'config');

        $this->mergeConfigFrom($source, 'prometheus-exporter');

        // Routes
        $this->loadRoutesFrom(__DIR__ . '/../Routes/routes.php');
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->singleton(PrometheusExporterContract::class, function () {
            return new PrometheusExporter();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() : array
    {
        return [
            PrometheusExporterContract::class
        ];
    }
}
