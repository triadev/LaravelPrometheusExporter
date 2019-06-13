<?php
namespace Triadev\PrometheusExporter\Controller;

use Illuminate\Http\Response;
use Prometheus\RenderTextFormat;
use Triadev\PrometheusExporter\PrometheusExporter;
use Laravel\Lumen\Routing\Controller;

class LumenController extends Controller
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
        
        return \response()->make(
            $renderer->render($this->prometheusExporter->getMetricFamilySamples())
        )->header('Content-Type', RenderTextFormat::MIME_TYPE);
    }
}
