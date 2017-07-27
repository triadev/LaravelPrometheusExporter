<?php
namespace Triadev\PrometheusExporter\Facade;

use Illuminate\Support\Facades\Facade;
use Triadev\PrometheusExporter\PrometheusExporter;

/**
 * Class PrometheusExporterFacade
 *
 * @author Christopher Lorke <christopher.lorke@gmx.de>
 * @package Triadev\PrometheusExporter\Facade
 */
class PrometheusExporterFacade extends Facade
{
    /**
     * Get facade accessor
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return PrometheusExporter::class;
    }
}
