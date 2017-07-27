<?php
namespace Triadev\PrometheusExporter\Controller;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Prometheus\RenderTextFormat;
use Triadev\PrometheusExporter\Contract\PrometheusExporterContract;

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
        /** @var PrometheusExporterContract $prometheusExporterService */
        $prometheusExporterService = app(PrometheusExporterContract::class);

        $renderer = new RenderTextFormat();

        return response($renderer->render($prometheusExporterService->getMetricFamilySamples()))
            ->header('Content-Type', $renderer::MIME_TYPE);
    }
}
