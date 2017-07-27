<?php
namespace Triadev\PrometheusExporter\Tests;

use Illuminate\Http\Response;
use Prometheus\CollectorRegistry;
use Prometheus\Storage\Adapter;
use Tests\TestCase;
use Triadev\PrometheusExporter\Controller\PrometheusExporterController;
use Triadev\PrometheusExporter\PrometheusExporter;

/**
 * Class PrometheusExporterTest
 *
 * @author Christopher Lorke <christopher.lorke@gmx.de>
 * @package Triadev\PrometheusExporter\Tests
 */
class PrometheusExporterTest extends TestCase
{
    /**
     * Build prometheus exporter
     *
     * @return PrometheusExporter
     */
    private function buildPrometheusExporter() : PrometheusExporter
    {
        return new PrometheusExporter(
            new CollectorRegistry(
                $this->app->make(Adapter::class)
            )
        );
    }

    /**
     * Build prometheus exporter controller
     *
     * @return PrometheusExporterController
     */
    private function buildPrometheusExporterController() : PrometheusExporterController
    {
        return new PrometheusExporterController(
            $this->buildPrometheusExporter()
        );
    }

    /**
     * @test
     * @group PrometheusExporter
     */
    public function it_test_to_inc_a_counter()
    {
        $prometheusExporterController = $this->buildPrometheusExporterController();

        $prometheusExporter = $this->buildPrometheusExporter();
        $prometheusExporter->incCounter(
            'phpunit_incCounter',
            ''
        );

        $response_before = $prometheusExporterController->metrics();

        $this->assertInstanceOf(Response::class, $response_before);
        $this->assertEquals(200, $response_before->getStatusCode());

        if (preg_match('/app_phpunit_incCounter (?<metric>[0-9]+)/', $response_before->getContent(), $matches)) {
            $metric_before = $matches['metric'];
        } else {
            throw new \Exception();
        }

        $prometheusExporter = $this->buildPrometheusExporter();
        $prometheusExporter->incCounter(
            'phpunit_incCounter',
            ''
        );

        $response_after = $prometheusExporterController->metrics();

        $this->assertInstanceOf(Response::class, $response_after);
        $this->assertEquals(200, $response_after->getStatusCode());

        if (preg_match('/app_phpunit_incCounter (?<metric>[0-9]+)/', $response_after->getContent(), $matches)) {
            $metric_after = $matches['metric'];
        } else {
            throw new \Exception();
        }

        $this->assertGreaterThan($metric_before, $metric_after);
    }

    /**
     * @test
     * @group PrometheusExporter
     */
    public function it_test_to_inc_by_counter()
    {
        $prometheusExporterController = $this->buildPrometheusExporterController();

        $prometheusExporter = $this->buildPrometheusExporter();
        $prometheusExporter->incByCounter(
            'phpunit_incByCounter',
            '',
            2
        );

        $response_before = $prometheusExporterController->metrics();

        $this->assertInstanceOf(Response::class, $response_before);
        $this->assertEquals(200, $response_before->getStatusCode());

        if (preg_match('/app_phpunit_incByCounter (?<metric>[0-9]+)/', $response_before->getContent(), $matches)) {
            $metric_before = $matches['metric'];
        } else {
            throw new \Exception();
        }

        $prometheusExporter = $this->buildPrometheusExporter();
        $prometheusExporter->incCounter(
            'phpunit_incByCounter',
            ''
        );

        $response_after = $prometheusExporterController->metrics();

        $this->assertInstanceOf(Response::class, $response_after);
        $this->assertEquals(200, $response_after->getStatusCode());

        if (preg_match('/app_phpunit_incByCounter (?<metric>[0-9]+)/', $response_after->getContent(), $matches)) {
            $metric_after = $matches['metric'];
        } else {
            throw new \Exception();
        }

        $this->assertGreaterThan($metric_before, $metric_after);
    }

    /**
     * @test
     * @group PrometheusExporter
     */
    public function it_test_to_set_a_gauge()
    {
        $prometheusExporterController = $this->buildPrometheusExporterController();

        $prometheusExporter = $this->buildPrometheusExporter();
        $prometheusExporter->setGauge(
            'phpunit_setGauge',
            '',
            1
        );

        $response_before = $prometheusExporterController->metrics();

        $this->assertInstanceOf(Response::class, $response_before);
        $this->assertEquals(200, $response_before->getStatusCode());

        if (preg_match('/app_phpunit_setGauge (?<metric>[0-9]+)/', $response_before->getContent(), $matches)) {
            $metric_before = $matches['metric'];
        } else {
            throw new \Exception();
        }

        $prometheusExporter = $this->buildPrometheusExporter();
        $prometheusExporter->setGauge(
            'phpunit_setGauge',
            '',
            2
        );

        $response_after = $prometheusExporterController->metrics();

        $this->assertInstanceOf(Response::class, $response_after);
        $this->assertEquals(200, $response_after->getStatusCode());

        if (preg_match('/app_phpunit_setGauge (?<metric>[0-9]+)/', $response_after->getContent(), $matches)) {
            $metric_after = $matches['metric'];
        } else {
            throw new \Exception();
        }

        $this->assertGreaterThan($metric_before, $metric_after);
    }
}
