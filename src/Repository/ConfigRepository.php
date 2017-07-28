<?php
namespace Triadev\PrometheusExporter\Repository;

/**
 * Class ConfigRepository
 *
 * @author Christopher Lorke <christopher.lorke@gmx.de>
 * @package Triadev\PrometheusExporter\Repository
 */
class ConfigRepository
{
    /**
     * @var array
     */
    private $config = [];

    /**
     * ConfigRepository constructor.
     */
    public function __construct()
    {
        if (class_exists(\Config::class)) {
            $this->config = \Config::get('prometheus-exporter');
        } else {
            $this->config = require (realpath(__DIR__.'/../Config/config.php'));
        }
    }

    /**
     * Get the full config
     *
     * @return array
     */
    public function getConfig() : array
    {
        return $this->config;
    }
}
