<?php
namespace Tests;

use Illuminate\Foundation\Testing\TestResponse;
use Triadev\PrometheusExporter\Contract\PrometheusExporterContract;

class PrometheusExporterTest extends TestCase
{
    /** @var PrometheusExporterContract */
    private $service;
    
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();
        
        $this->service = app(PrometheusExporterContract::class);
    }
    
    private function getMetricResponse() : TestResponse
    {
        return $this->get('/triadev/pe/metrics');
    }
    
    private function getMetricValue(string $name) : ?int
    {
        $pattern = sprintf('/app_%s (?<metric>[0-9]+)/', $name);
        
        if (preg_match($pattern, $this->getMetricResponse()->getContent(), $matches)) {
            return $matches['metric'];
        }
        
        return null;
    }
    
    /**
     * @test
     */
    public function it_inc_a_counter()
    {
        $this->service->incCounter('phpunit_incCounter', '');
        
        $metricBefore = $this->getMetricValue('phpunit_incCounter');
    
        $this->service->incCounter('phpunit_incCounter', '');
        
        $metricAfter = $this->getMetricValue('phpunit_incCounter');
    
        $this->assertGreaterThan($metricBefore, $metricAfter);
    }
    
    /**
     * @test
     */
    public function it_inc_by_counter()
    {
        $this->service->incByCounter('phpunit_incByCounter', '', 10);
        
        $this->assertEquals(10, $this->getMetricValue('phpunit_incByCounter'));
    }
    
    /**
     * @test
     */
    public function it_set_a_gauge()
    {
        $this->service->setGauge('phpunit_setGauge', '', 2);
        
        $this->assertEquals(2, $this->getMetricValue('phpunit_setGauge'));
    }
    
    /**
     * @test
     */
    public function it_inc_gauge()
    {
        $this->service->incGauge('phpunit_incGauge', '');
        
        $this->assertEquals(1, $this->getMetricValue('phpunit_incGauge'));
    
        $this->service->incGauge('phpunit_incGauge', '');
    
        $this->assertEquals(2, $this->getMetricValue('phpunit_incGauge'));
    }
    
    /**
     * @test
     */
    public function it_inc_by_gauge()
    {
        $this->service->incByGauge('phpunit_incByGauge', '', 2);
        
        $this->assertEquals(2, $this->getMetricValue('phpunit_incByGauge'));
    }
    
    /**
     * @test
     */
    public function it_set_a_histogram()
    {
        $this->service->setHistogram('phpunit_setHistogram', '', 1);
        $this->service->setHistogram('phpunit_setHistogram', '', 2);

        $this->assertEquals(3, $this->getMetricValue('phpunit_setHistogram_sum'));
    }
}
