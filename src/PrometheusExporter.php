<?php
namespace Triadev\PrometheusExporter;

use Prometheus\PushGateway;
use Triadev\PrometheusExporter\Contract\PrometheusExporterContract;
use Prometheus\CollectorRegistry;
use Prometheus\MetricFamilySamples;
use Prometheus\Exception\MetricNotFoundException;

class PrometheusExporter implements PrometheusExporterContract
{
    /**
     * @var CollectorRegistry
     */
    protected $registry;

    /**
     * LpeManager constructor.
     * @param CollectorRegistry $registry
     */
    public function __construct(CollectorRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * Get metric family samples
     *
     * @return MetricFamilySamples[]
     */
    public function getMetricFamilySamples()
    {
        return $this->registry->getMetricFamilySamples();
    }
    
    /**
     * @inheritdoc
     */
    public function incCounter($name, $help, $namespace = null, array $labelKeys = [], array $labelValues = [])
    {
        $namespace = $this->getNamespace($namespace);
        
        try {
            $counter = $this->registry->getCounter($namespace, $name);
        } catch (MetricNotFoundException $e) {
            $counter = $this->registry->registerCounter($namespace, $name, $help, $labelKeys);
        }

        $counter->inc($labelValues);

        $this->pushGateway($this->registry, 'inc');
    }
    
    /**
     * @inheritdoc
     */
    public function incByCounter(
        $name,
        $help,
        $value,
        $namespace = null,
        array $labelKeys = [],
        array $labelValues = []
    ) {
        $namespace = $this->getNamespace($namespace);

        try {
            $counter = $this->registry->getCounter($namespace, $name);
        } catch (MetricNotFoundException $e) {
            $counter = $this->registry->registerCounter($namespace, $name, $help, $labelKeys);
        }

        $counter->incBy($value, $labelValues);

        $this->pushGateway($this->registry, 'incBy');
    }
    
    /**
     * @inheritdoc
     */
    public function setGauge($name, $help, $value, $namespace = null, array $labelKeys = [], array $labelValues = [])
    {
        $namespace = $this->getNamespace($namespace);

        try {
            $gauge = $this->registry->getGauge($namespace, $name);
        } catch (MetricNotFoundException $e) {
            $gauge = $this->registry->registerGauge($namespace, $name, $help, $labelKeys);
        }

        $gauge->set($value, $labelValues);

        $this->pushGateway($this->registry, 'gauge');
    }
    
    /**
     * @inheritdoc
     */
    public function incGauge($name, $help, $namespace = null, array $labelKeys = [], array $labelValues = [])
    {
        $namespace = $this->getNamespace($namespace);

        try {
            $gauge = $this->registry->getGauge($namespace, $name);
        } catch (MetricNotFoundException $e) {
            $gauge = $this->registry->registerGauge($namespace, $name, $help, $labelKeys);
        }

        $gauge->inc($labelValues);

        $this->pushGateway($this->registry, 'inc');
    }
    
    /**
     * @inheritdoc
     */
    public function incByGauge(
        $name,
        $help,
        $value,
        $namespace = null,
        array $labelKeys = [],
        array $labelValues = []
    ) {
        $namespace = $this->getNamespace($namespace);

        try {
            $gauge = $this->registry->getGauge($namespace, $name);
        } catch (MetricNotFoundException $e) {
            $gauge = $this->registry->registerGauge($namespace, $name, $help, $labelKeys);
        }

        $gauge->incBy($value, $labelValues);

        $this->pushGateway($this->registry, 'incBy');
    }
    
    /**
     * @inheritdoc
     */
    public function setHistogram(
        $name,
        $help,
        $value,
        $namespace = null,
        array $labelKeys = [],
        array $labelValues = [],
        ?array $buckets = null
    ) {
        $namespace = $this->getNamespace($namespace);

        try {
            $histogram = $this->registry->getHistogram($namespace, $name);
        } catch (MetricNotFoundException $e) {
            $histogram = $this->registry->registerHistogram($namespace, $name, $help, $labelKeys, $buckets);
        }

        $histogram->observe($value, $labelValues);

        $this->pushGateway($this->registry, 'histogram');
    }
    
    private function getNamespace(?string $namespace = null) : string
    {
        if (!$namespace) {
            $namespace = config('prometheus-exporter.namespace');
        }

        return $namespace;
    }
    
    private function pushGateway(CollectorRegistry $registry, string $job, ?array $groupingKey = null)
    {
        if (config('prometheus-exporter.adapter') == 'push') {
            $pushGateway = new PushGateway(config('prometheus-exporter.push_gateway.address'));
            
            $pushGateway->push(
                $registry,
                $job,
                $groupingKey
            );
        }
    }
}
