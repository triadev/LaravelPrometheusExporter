<?php
namespace Triadev\PrometheusExporter\Controller;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Prometheus\RenderTextFormat;
use PrometheusExporter;

/**
 * Class PrometheusExporterController
 *
 * @author Christopher Lorke <christopher.lorke@gmx.de>
 * @package Triadev\PrometheusExporter\Controller
 */
class PrometheusExporterController extends Controller
{
    /**
     * metrics
     *
     * Expose metrics for prometheus
     *
     * @return Response
     */
    public function metrics() : Response
    {
        $renderer = new RenderTextFormat();

        return response($renderer->render(PrometheusExporter::getMetricFamilySamples()))
            ->header('Content-Type', $renderer::MIME_TYPE);
    }
}
