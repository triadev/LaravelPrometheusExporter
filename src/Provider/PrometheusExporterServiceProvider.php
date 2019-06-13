<?php
namespace Triadev\PrometheusExporter\Provider;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Prometheus\Storage\InMemory;
use Triadev\PrometheusExporter\Contract\PrometheusExporterContract;
use Prometheus\Storage\Redis;
use Prometheus\Storage\APC;
use Prometheus\Storage\Adapter;
use Triadev\PrometheusExporter\Middleware\RequestPerRoute;
use Triadev\PrometheusExporter\PrometheusExporter;

class PrometheusExporterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootConfig();
        $this->bootRoutes();
    }
    
    private function bootConfig()
    {
        $source = realpath(__DIR__ . '/../Config/config.php');
    
        if (class_exists('Illuminate\Foundation\Application', false)) {
            $this->publishes([
                __DIR__ . '/../Config/config.php' => config_path('prometheus-exporter.php'),
            ], 'config');
        } elseif (class_exists('Laravel\Lumen\Application', false)) {
            $this->app/** @scrutinizer ignore-call */->configure('prometheus-exporter');
        }
    
        $this->mergeConfigFrom($source, 'prometheus-exporter');
    }
    
    private function bootRoutes()
    {
        if (class_exists('Illuminate\Foundation\Application', false)) {
            $this->loadRoutesFrom(__DIR__ . '/../Routes/routes.php');
        }
    }

    /**
     * Register the service provider.
     *
     * @throws \ErrorException
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../Config/config.php', 'prometheus-exporter');
    
        $this->registerAdapter();
        $this->registerMiddlewareForRequestMetrics();
    
        $this->app->bind(
            PrometheusExporterContract::class,
            PrometheusExporter::class,
            true
        );
    }
    
    /**
     * @throws \ErrorException
     */
    private function registerAdapter()
    {
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
            case 'inmemory':
                $this->app->bind(Adapter::class, InMemory::class);
                break;
            default:
                throw new \ErrorException('"prometheus-exporter.adapter" must be either apc or redis');
        }
    }
    
    private function registerMiddlewareForRequestMetrics()
    {
        if (class_exists('Illuminate\Foundation\Application', false)) {
            /** @var Router $router */
            $router = $this->app['router'];
            $router->aliasMiddleware('lpe.requestPerRoute', RequestPerRoute::class);
        }
    }
}
