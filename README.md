# LaravelPrometheusExporter

[![Software license][ico-license]](LICENSE)
[![Travis][ico-travis]][link-travis]
[![Coveralls](https://coveralls.io/repos/github/triadev/LaravelPrometheusExporter/badge.svg?branch=master)](https://coveralls.io/github/triadev/LaravelPrometheusExporter?branch=master)
[![CodeCov](https://codecov.io/gh/triadev/LaravelPrometheusExporter/branch/master/graph/badge.svg)](https://codecov.io/gh/triadev/LaravelPrometheusExporter)
[![Latest stable][ico-version-stable]][link-packagist]
[![Monthly installs][ico-downloads-monthly]][link-downloads]
[![Total Downloads](https://img.shields.io/packagist/dt/triadev/laravel-prometheus-exporter.svg?style=flat-square)](https://packagist.org/packages/triadev/laravel-prometheus-exporter)

A laravel and lumen service provider to export metrics for prometheus.

## Supported laravel versions
[![Laravel 5.5][icon-l55]][link-laravel]
[![Laravel 5.6][icon-l56]][link-laravel]
[![Laravel 5.7][icon-l57]][link-laravel]

## Main features
- Metrics with APC
- Metrics with Redis
- Metrics with InMemory
- Metrics with the push gateway
- Request per route middleware (total and duration metrics)

## Installation

### Composer
> composer require triadev/laravel-prometheus-exporter

### Application

The package is registered through the package discovery of laravel and Composer.
>https://laravel.com/docs/5.7/packages

Once installed you can now publish your config file and set your correct configuration for using the package.
```php
php artisan vendor:publish --provider="Triadev\PrometheusExporter\Provider\PrometheusExporterServiceProvider" --tag="config"
```

This will create a file ```config/prometheus-exporter.php```.

## Configuration
| Key        | Env | Value           | Description  | Default |
|:-------------:|:-------------:|:-------------:|:-----:|:-----:|
| adapter | PROMETHEUS_ADAPTER | STRING | apc, redis, inmemory or push | apc |
| namespace | --- | STRING | default: app | app |
| namespace_http | --- | STRING | namespace for "RequestPerRoute-Middleware metrics" | http |
| redis.host | PROMETHEUS_REDIS_HOST | STRING | redis host | 127.0.0.1
| redis.port | PROMETHEUS_REDIS_PORT | INTEGER | redis port | 6379 |
| redis.timeout | --- | FLOAT | redis timeout | 0.1 |
| redis.read_timeout | --- | INTEGER | redis read timeout | 10 |
| push_gateway.address | PROMETHEUS_PUSH_GATEWAY_ADDRESS | STRING | push gateway address | localhost:9091 |
| buckets_per_route | --- | STRING | histogram buckets for "RequestPerRoute-Middleware" | --- |

### buckets_per_route
```
'buckets_per_route' => [
    ROUTE-NAME => [10,20,50,100,200],
    ...
]
```

## Usage

### Endpoint to get metrics
>triadev/pe/metrics

### Middleware

#### RequestPerRoute
A middleware to build metrics for "request_total" and "requests_latency_milliseconds" per route.

##### Alias
>lpe.requestPerRoute

##### Metrics
1. requests_total (inc)
2. requests_latency_milliseconds (histogram)

##### Example
```php
$router->get('requestPerRoute', function () {
    return 'valid';
})->middleware('lpe.requestPerRoute')->name('requestPerRoute');
```

>app_requests_latency_milliseconds_bucket{route="requestPerRoute",method="GET",status_code="200",le="0.005"} 0
>...
>app_requests_latency_milliseconds_count{route="requestPerRoute",method="GET",status_code="200"} 1
>app_requests_latency_milliseconds_sum{route="requestPerRoute",method="GET",status_code="200"} 6
>app_requests_total{route="requestPerRoute",method="GET",status_code="200"} 1

## Roadmap
- histogram buckets per route (RequestPerRoute)

## Reporting Issues
If you do find an issue, please feel free to report it with GitHub's bug tracker for this project.

Alternatively, fork the project and make a pull request. :)

## Testing
1. docker-compose up
2. docker exec fpm ./vendor/phpunit/phpunit/phpunit

## Contributing
Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits
- [Christopher Lorke][link-author]
- [All Contributors][link-contributors]

## Other

### Project related links
- [Wiki](https://github.com/triadev/LaravelPrometheusExporter/wiki)
- [Issue tracker](https://github.com/triadev/LaravelPrometheusExporter/issues)

### Author
- [Christopher Lorke](mailto:christopher.lorke@gmx.de)

### License
The code for LaravelPrometheusExporter is distributed under the terms of the MIT license (see [LICENSE](LICENSE)).

[ico-license]: https://img.shields.io/github/license/triadev/LaravelPrometheusExporter.svg?style=flat-square
[ico-version-stable]: https://img.shields.io/packagist/v/triadev/laravel-prometheus-exporter.svg?style=flat-square
[ico-downloads-monthly]: https://img.shields.io/packagist/dm/triadev/laravel-prometheus-exporter.svg?style=flat-square
[ico-travis]: https://travis-ci.org/triadev/LaravelPrometheusExporter.svg?branch=master

[link-packagist]: https://packagist.org/packages/triadev/laravel-prometheus-exporter
[link-downloads]: https://packagist.org/packages/triadev/laravel-prometheus-exporter/stats
[link-travis]: https://travis-ci.org/triadev/LaravelPrometheusExporter

[icon-l55]: https://img.shields.io/badge/Laravel-5.5-brightgreen.svg?style=flat-square
[icon-l56]: https://img.shields.io/badge/Laravel-5.6-brightgreen.svg?style=flat-square
[icon-l57]: https://img.shields.io/badge/Laravel-5.7-brightgreen.svg?style=flat-square

[link-laravel]: https://laravel.com
[link-elasticsearch]: https://www.elastic.co/
[link-author]: https://github.com/triadev
[link-contributors]: ../../contributors
