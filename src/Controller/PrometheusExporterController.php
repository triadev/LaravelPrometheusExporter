<?php
namespace Triadev\PrometheusExporter\Controller;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Prometheus\RenderTextFormat;
use Triadev\PrometheusExporter\PrometheusExporter;

/**
 * Class PrometheusExporterController
 *
 * @author Christopher Lorke <christopher.lorke@gmx.de>
 * @package Triadev\PrometheusExporter\Controller
 */
class PrometheusExporterController extends Controller
{
    /**
     * @var PrometheusExporter
     */
    protected $prometheusExporter;

    /**
     * PrometheusExporterController constructor.
     *
     * @param PrometheusExporter $prometheusExporter
     */
    public function __construct(PrometheusExporter $prometheusExporter)
    {
        $this->prometheusExporter = $prometheusExporter;
    }

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

        return response($renderer->render($this->prometheusExporter->getMetricFamilySamples()))
            ->header('Content-Type', $renderer::MIME_TYPE);
    }
}
