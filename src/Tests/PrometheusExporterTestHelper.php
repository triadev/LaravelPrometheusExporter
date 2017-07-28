<?php
namespace Triadev\PrometheusExporter\Tests;

use Prometheus\CollectorRegistry;
use Prometheus\Storage\APC;
use Triadev\PrometheusExporter\Controller\PrometheusExporterController;
use Triadev\PrometheusExporter\PrometheusExporter;

/**
 * Trait PrometheusExporterTestHelper
 *
 * @author Christopher Lorke <christopher.lorke@gmx.de>
 * @package Triadev\PrometheusExporter\Tests
 */
trait PrometheusExporterTestHelper
{
    /**
     * Build prometheus exporter
     *
     * @return PrometheusExporter
     */
    public function buildPrometheusExporter() : PrometheusExporter
    {
        return new PrometheusExporter(
            new CollectorRegistry(
                new APC()
            )
        );
    }

    /**
     * Build prometheus exporter controller
     *
     * @return PrometheusExporterController
     */
    public function buildPrometheusExporterController() : PrometheusExporterController
    {
        return new PrometheusExporterController(
            $this->buildPrometheusExporter()
        );
    }
}
