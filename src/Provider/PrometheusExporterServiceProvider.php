<?php
namespace Triadev\PrometheusExporter\Provider;

use Illuminate\Support\ServiceProvider;
use Triadev\PrometheusExporter\Contract\PrometheusExporterContract;
use Prometheus\Storage\Redis;
use Prometheus\Storage\APC;
use Prometheus\Storage\Adapter;

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

        if (class_exists('Illuminate\Foundation\Application', false)) {
            $this->loadRoutesFrom(__DIR__ . '/../Routes/routes.php');
        }
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../Config/config.php', 'prometheus-exporter');

        switch (config('prometheus-exporter.adapter')) {
            case 'apc':
                $this->app->bind(Adapter::class, APC::class);
                break;
            case 'redis':
                $this->app->bind(Adapter::class, function () {
                    return new Redis(config('prometheus-exporter.redis'));
                });
                break;
            case 'push':
                $this->app->bind(Adapter::class, APC::class);
                break;
            default:
                throw new \ErrorException('"prometheus-exporter.adapter" must be either apc or redis');
        }
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
