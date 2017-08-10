<?php
namespace Triadev\PrometheusExporter;

use Triadev\PrometheusExporter\Contract\PrometheusExporterContract;
use Prometheus\CollectorRegistry;
use Prometheus\MetricFamilySamples;
use Prometheus\Exception\MetricNotFoundException;
use Illuminate\Support\Facades\Config;
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
        if (!$namespace) {
            $namespace = (new ConfigRepository())->getConfig()['namespace'];
        }

        try {
            $counter = $this->registry->getCounter($namespace, $name);
        } catch (MetricNotFoundException $e) {
            $counter = $this->registry->registerCounter($namespace, $name, $help, $labelKeys);
        }

        $counter->inc($labelValues);
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
        if (!$namespace) {
            $namespace = (new ConfigRepository())->getConfig()['namespace'];
        }

        try {
            $counter = $this->registry->getCounter($namespace, $name);
        } catch (MetricNotFoundException $e) {
            $counter = $this->registry->registerCounter($namespace, $name, $help, $labelKeys);
        }

        $counter->incBy($value, $labelValues);
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
        if (!$namespace) {
            $namespace = (new ConfigRepository())->getConfig()['namespace'];
        }

        try {
            $gauge = $this->registry->getGauge($namespace, $name);
        } catch (MetricNotFoundException $e) {
            $gauge = $this->registry->registerGauge($namespace, $name, $help, $labelKeys);
        }

        $gauge->set($value, $labelValues);
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
        if (!$namespace) {
            $namespace = (new ConfigRepository())->getConfig()['namespace'];
        }

        try {
            $histogram = $this->registry->getHistogram($namespace, $name);
        } catch (MetricNotFoundException $e) {
            $histogram = $this->registry->registerHistogram($namespace, $name, $help, $labelKeys, $buckets);
        }

        $histogram->observe($value, $labelValues);
    }
}
