<?php
namespace Triadev\PrometheusExporter\Contract;

use Prometheus\MetricFamilySamples;

/**
 * Interface PrometheusExporterContract
 *
 * @author Christopher Lorke <christopher.lorke@gmx.de>
 * @package Triadev\PrometheusExporter\Contract
 */
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
     */
    public function setGauge($name, $help, $value, $namespace = null, array $labelKeys = [], array $labelValues = []);

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
    );
}
