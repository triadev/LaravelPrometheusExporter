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
    private $_registry;

    /**
     * LpeManager constructor.
     *
     * @param CollectorRegistry $registry
     */
    public function __construct(CollectorRegistry $registry) {
        $this->_registry = $registry;
    }

    /**
     * Get metric family samples
     *
     * @return MetricFamilySamples[]
     */
    public function getMetricFamilySamples() {
        return $this->_registry->getMetricFamilySamples();
    }
    
    /**
     * @inheritdoc
     */
    public function incCounter($name, $help, $namespace = null, array $labelKeys = [], array $labelValues = []) {
        $namespace = $this->getNamespace($namespace);
        
        try {
            $counter = $this->_registry->getCounter($namespace, $name);
        } catch (MetricNotFoundException $e) {
            $counter = $this->_registry->registerCounter($namespace, $name, $help, $labelKeys);
        }

        $counter->inc($labelValues);

        $this->pushGateway($this->_registry, 'inc');
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
            $counter = $this->_registry->getCounter($namespace, $name);
        } catch (MetricNotFoundException $e) {
            $counter = $this->_registry->registerCounter($namespace, $name, $help, $labelKeys);
        }

        $counter->incBy($value, $labelValues);

        $this->pushGateway($this->_registry, 'incBy');
    }
    
    /**
     * @inheritdoc
     */
    public function setGauge($name, $help, $value, $namespace = null, array $labelKeys = [], array $labelValues = []) {
        $namespace = $this->getNamespace($namespace);

        try {
            $gauge = $this->_registry->getGauge($namespace, $name);
        } catch (MetricNotFoundException $e) {
            $gauge = $this->_registry->registerGauge($namespace, $name, $help, $labelKeys);
        }

        $gauge->set($value, $labelValues);

        $this->pushGateway($this->_registry, 'gauge');
    }
    
    /**
     * @inheritdoc
     */
    public function incGauge($name, $help, $namespace = null, array $labelKeys = [], array $labelValues = []) {
        $namespace = $this->getNamespace($namespace);

        try {
            $gauge = $this->_registry->getGauge($namespace, $name);
        } catch (MetricNotFoundException $e) {
            $gauge = $this->_registry->registerGauge($namespace, $name, $help, $labelKeys);
        }

        $gauge->inc($labelValues);

        $this->pushGateway($this->_registry, 'inc');
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
            $gauge = $this->_registry->getGauge($namespace, $name);
        } catch (MetricNotFoundException $e) {
            $gauge = $this->_registry->registerGauge($namespace, $name, $help, $labelKeys);
        }

        $gauge->incBy($value, $labelValues);

        $this->pushGateway($this->_registry, 'incBy');
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
            $histogram = $this->_registry->getHistogram($namespace, $name);
        } catch (MetricNotFoundException $e) {
            $histogram = $this->_registry->registerHistogram($namespace, $name, $help, $labelKeys, $buckets);
        }

        $histogram->observe($value, $labelValues);

        $this->pushGateway($this->_registry, 'histogram');
    }
    
    private function getNamespace(?string $namespace = null) : string {
        if (!$namespace) {
            $namespace = config('prometheus-exporter.namespace');
        }

        return $namespace;
    }
    
    private function pushGateway(CollectorRegistry $registry, string $job, ?array $groupingKey = null) {
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
