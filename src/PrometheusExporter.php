<?php
namespace Triadev\PrometheusExporter;

use Prometheus\PushGateway;
use Triadev\PrometheusExporter\Contract\PrometheusExporterContract;
use Prometheus\CollectorRegistry;
use Prometheus\MetricFamilySamples;
use Prometheus\Exception\MetricNotFoundException;
use Triadev\PrometheusExporter\Repository\ConfigRepository;

/**
 * Class PrometheusExporter
 *
 * @author Christopher Lorke <christopher.lorke@gmx.de>
 * @package Triadev\PrometheusExporter
 */
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
     * inc
     *
     * @param string $name
     * @param string $help
     * @param string|null $namespace
     * @param array $labelKeys
     * @param array $labelValues
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
     * incBy
     *
     * @param string $name
     * @param string $help
     * @param float $value
     * @param string|null $namespace
     * @param array $labelKeys
     * @param array $labelValues
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
     * Set gauge
     *
     * @param string $name
     * @param string $help
     * @param int $value
     * @param null|string $namespace
     * @param array $labelKeys
     * @param array $labelValues
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
     * inc Gauge
     *
     * @param string $name
     * @param string $help
     * @param string|null $namespace
     * @param array $labelKeys
     * @param array $labelValues
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
     * incBy Gauge
     *
     * @param string $name
     * @param string $help
     * @param float $value
     * @param string|null $namespace
     * @param array $labelKeys
     * @param array $labelValues
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
     * Set histogram
     *
     * @param string $name
     * @param string $help
     * @param float $value
     * @param null|string $namespace
     * @param array $labelKeys
     * @param array $labelValues
     * @param array|null $buckets
     */
    public function setHistogram(
        $name,
        $help,
        $value,
        $namespace = null,
        array $labelKeys = [],
        array $labelValues = [],
        array $buckets = null
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

    /**
     * Get config
     *
     * @return array
     */
    private function getConfig() : array
    {
        return (new ConfigRepository())->getConfig();
    }

    /**
     * Get namespace
     *
     * @param null|string $namespace
     * @return string
     */
    private function getNamespace(?string $namespace = null) : string
    {
        $config = $this->getConfig();

        if (!$namespace) {
            $namespace = $config['namespace'];
        }

        return $namespace;
    }

    /**
     * Push gateway
     *
     * @param CollectorRegistry $registry
     * @param string $job
     * @param array|null $groupingKey
     */
    private function pushGateway(CollectorRegistry $registry, string $job, ?array $groupingKey = null)
    {
        $config = (new ConfigRepository())->getConfig();

        if ($config['adapter'] == 'push') {
            $pushGateway = new PushGateway($config['push_gateway']['address']);
            $pushGateway->push(
                $registry,
                $job,
                $groupingKey
            );
        }
    }
}
