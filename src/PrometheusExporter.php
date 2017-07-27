<?php
namespace Triadev\PrometheusExporter;

use Triadev\PrometheusExporter\Contract\PrometheusExporterContract;
use Prometheus\CollectorRegistry;
use Prometheus\MetricFamilySamples;
use Prometheus\Exception\MetricNotFoundException;

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
     * @param array $labels
     * @param array $data
     */
    public function incCounter($name, $help, $namespace = null, array $labels = [], array $data = [])
    {
        if (!$namespace) {
            $namespace = config('prometheus-exporter.namespace');
        }

        try {
            $counter = $this->registry->getCounter($namespace, $name);
        } catch (MetricNotFoundException $e) {
            $counter = $this->registry->registerCounter($namespace, $name, $help, $labels);
        }

        $counter->inc($data);
    }

    /**
     * incBy
     *
     * @param string $name
     * @param string $help
     * @param string|null $namespace
     * @param array $labels
     * @param float $value
     * @param array $data
     */
    public function incByCounter($name, $help, $value, $namespace = null, array $labels = [], array $data = [])
    {
        if (!$namespace) {
            $namespace = config('prometheus-exporter.namespace');
        }

        try {
            $counter = $this->registry->getCounter($namespace, $name);
        } catch (MetricNotFoundException $e) {
            $counter = $this->registry->registerCounter($namespace, $name, $help, $labels);
        }

        $counter->incBy($value, $data);
    }

    /**
     * Set gauge
     *
     * @param string $name
     * @param string $help
     * @param int $value
     * @param null|string $namespace
     * @param array $labels
     */
    public function setGauge($name, $help, $value, $namespace = null, array $labels = [])
    {
        if (!$namespace) {
            $namespace = config('prometheus-exporter.namespace');
        }

        try {
            $gauge = $this->registry->getGauge($namespace, $name);
        } catch (MetricNotFoundException $e) {
            $gauge = $this->registry->registerGauge($namespace, $name, $help, $labels);
        }

        $gauge->set($value, $labels);
    }
}
