<?php
namespace Triadev\PrometheusExporter\Contract;

use Prometheus\Exception\MetricsRegistrationException;
use Prometheus\MetricFamilySamples;

interface PrometheusExporterContract
{
    /**
     * Get metric family samples
     *
     * @return MetricFamilySamples[]
     */
    public function getMetricFamilySamples();

    /**
     * inc
     *
     * @param string $name
     * @param string $help
     * @param string|null $namespace
     * @param array $labelKeys
     * @param array $labelValues
     *
     * @throws MetricsRegistrationException
     */
    public function incCounter($name, $help, $namespace = null, array $labelKeys = [], array $labelValues = []);

    /**
     * incBy
     *
     * @param string $name
     * @param string $help
     * @param float $value
     * @param string|null $namespace
     * @param array $labelKeys
     * @param array $labelValues
     *
     * @throws MetricsRegistrationException
     */
    public function incByCounter(
        $name,
        $help,
        $value,
        $namespace = null,
        array $labelKeys = [],
        array $labelValues = []
    );

    /**
     * Set gauge
     *
     * @param string $name
     * @param string $help
     * @param int $value
     * @param null|string $namespace
     * @param array $labelKeys
     * @param array $labelValues
     *
     * @throws MetricsRegistrationException
     */
    public function setGauge($name, $help, $value, $namespace = null, array $labelKeys = [], array $labelValues = []);
    
    /**
     * Inc by gauge
     *
     * @param $name
     * @param $help
     * @param null $namespace
     * @param array $labelKeys
     * @param array $labelValues
     *
     * @throws MetricsRegistrationException
     */
    public function incGauge($name, $help, $namespace = null, array $labelKeys = [], array $labelValues = []);
    
    /**
     * incBy Gauge
     *
     * @param string $name
     * @param string $help
     * @param float $value
     * @param string|null $namespace
     * @param array $labelKeys
     * @param array $labelValues
     *
     * @throws \Prometheus\Exception\MetricsRegistrationException
     */
    public function incByGauge(
        $name,
        $help,
        $value,
        $namespace = null,
        array $labelKeys = [],
        array $labelValues = []
    );
    
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
     *
     * @throws MetricsRegistrationException
     */
    public function setHistogram(
        $name,
        $help,
        $value,
        $namespace = null,
        array $labelKeys = [],
        array $labelValues = [],
        ?array $buckets = null
    );
}
