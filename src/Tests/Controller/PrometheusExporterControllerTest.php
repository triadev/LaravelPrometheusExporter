<?php
namespace Triadev\PrometheusExporter\Tests\Controller;

use Illuminate\Http\Response;
use Prometheus\RenderTextFormat;
use Triadev\PrometheusExporter\Tests\PrometheusExporterTestHelper;

/**
 * Class PrometheusExporterControllerTest
 *
 * @author Christopher Lorke <christopher.lorke@gmx.de>
 * @package Triadev\PrometheusExporter\Tests\Controller
 */
class PrometheusExporterControllerTest extends \PHPUnit_Framework_TestCase
{
    use PrometheusExporterTestHelper;

    /**
     * @test
     * @group PrometheusExporter
     */
    public function it_shows_the_metrics_response()
    {
        $prometheusExporterController = $this->buildPrometheusExporterController();

        $response = $prometheusExporterController->metrics();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(RenderTextFormat::MIME_TYPE, $response->headers->get('content-type'));
    }
}
