<?php
namespace Triadev\PrometheusExporter\Middleware;

use Illuminate\Http\Request;
use Closure;
use Illuminate\Http\Response;
use Prometheus\Exception\MetricsRegistrationException;
use Triadev\PrometheusExporter\Contract\PrometheusExporterContract;

class RequestPerRoute
{
    /** @var PrometheusExporterContract */
    private $prometheusExporter;
    
    /**
     * RequestPerRoute constructor.
     * @param PrometheusExporterContract $prometheusExporter
     */
    public function __construct(PrometheusExporterContract $prometheusExporter)
    {
        $this->prometheusExporter = $prometheusExporter;
    }
    
    /**
     * Handle an incoming request.
     *
     * @param  Request $request
     * @param  \Closure $next
     * @return mixed
     *
     * @throws MetricsRegistrationException
     */
    public function handle(Request $request, Closure $next)
    {
        $start = microtime(true);
    
        /** @var Response $response */
        $response = $next($request);
    
        $durationMilliseconds = (microtime(true) - $start) * 1000.0;
        
        $path = $request->path();
        $method = $request->getMethod();
        $status = $response->getStatusCode();
        
        $this->requestCountMetric($path, $method, $status);
        $this->requestLatencyMetric($path, $method, $status, $durationMilliseconds);
    
        return $response;
    }
    
    /**
     * @param string $routeName
     * @param string $method
     * @param int $status
     *
     * @throws MetricsRegistrationException
     */
    private function requestCountMetric(string $routeName, string $method, int $status)
    {
        $this->prometheusExporter->incCounter(
            'requests_total',
            'the number of http requests',
            config('prometheus_exporter.namespace_http'),
            [
                'route',
                'method',
                'status_code'
            ],
            [
                $routeName,
                $method,
                $status
            ]
        );
    }
    
    /**
     * @param string $routeName
     * @param string $method
     * @param int $status
     * @param int $duration
     *
     * @throws MetricsRegistrationException
     */
    private function requestLatencyMetric(string $routeName, string $method, int $status, int $duration)
    {
        $bucketsPerRoute = null;
        
        if ($bucketsPerRouteConfig = config('prometheus-exporter.buckets_per_route')) {
            $bucketsPerRoute = array_get($bucketsPerRouteConfig, $routeName);
        }
        
        $this->prometheusExporter->setHistogram(
            'requests_latency_milliseconds',
            'duration of requests',
            $duration,
            config('prometheus_exporter.namespace_http'),
            [
                'route',
                'method',
                'status_code'
            ],
            [
                $routeName,
                $method,
                $status
            ],
            $bucketsPerRoute
        );
    }
}
