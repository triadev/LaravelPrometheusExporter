<?php
namespace Triadev\PrometheusExporter\Tests\Controller;

use Illuminate\Http\Response;
use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;
use Tests\TestCase;
use Triadev\PrometheusExporter\Controller\PrometheusExporterController;
use Triadev\PrometheusExporter\PrometheusExporter;
use Prometheus\Storage\Adapter;

/**
 * Class PrometheusExporterControllerTest
 *
 * @author Christopher Lorke <christopher.lorke@gmx.de>
 * @package Triadev\PrometheusExporter\Tests\Controller
 */
class PrometheusExporterControllerTest extends TestCase
{
    /**
     * @test
     * @group PrometheusExporter
     */
    public function it_shows_the_metrics_response()
    {
        $prometheusExporterController = new PrometheusExporterController(
            new PrometheusExporter(
                new CollectorRegistry(
                    $this->app->make(Adapter::class)
                )
            )
        );

        $response = $prometheusExporterController->metrics();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(RenderTextFormat::MIME_TYPE, $response->headers->get('content-type'));
    }
}
