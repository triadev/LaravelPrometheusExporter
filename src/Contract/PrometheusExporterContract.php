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
     * incCounter
     *
     * @param string $name
     * @param string $help
     * @param string|null $namespace
     * @param array $labels
     * @param array $data
     */
    public function incCounter(
        $name,
        $help,
        $namespace = null,
        array $labels = [],
        array $data = []
    );

    /**
     * incByCounter
     *
     * @param string $name
     * @param string $help
     * @param float $value
     * @param string|null $namespace
     * @param array $labels
     * @param array $data
     */
    public function incByCounter(
        $name,
        $help,
        $value,
        $namespace = null,
        array $labels = [],
        array $data = []
    );

    /**
     * Set gauge
     *
     * @param string $name
     * @param string $help
     * @param int $value
     * @param null|string $namespace
     * @param array $labels
     */
    public function setGauge($name, $help, $value, $namespace = null, array $labels = []);
}
