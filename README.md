# LaravelPrometheusExporter

[![Software license][ico-license]](LICENSE)
[![Latest stable][ico-version-stable]][link-packagist]
[![Monthly installs][ico-downloads-monthly]][link-downloads]
[![Travis][ico-travis]][link-travis]

A laravel and lumen service provider to export metrics for prometheus.

## Main features
- Metrics with APC
- Metrics with Redis
- Metrics with the push gateway

## Installation

### Composer
> composer require triadev/laravel-prometheus-exporter

### Application
Register the service provider in the config/app.php (Laravel) or in the bootstrap/app.php (Lumen).
```
'providers' => [
    \Triadev\PrometheusExporter\Provider\PrometheusExporterServiceProvider::class
]
```

Add the facade in the config/app.php (Laravel):
```
'aliases' => [
    'PrometheusExporter' => \Triadev\PrometheusExporter\Facade\PrometheusExporterFacade::class
]
```

Add the facade in the bootstrap/app.php (Lumen):
```
if (!class_exists('PrometheusExporter')) {
    class_alias(\Triadev\PrometheusExporter\Facade\PrometheusExporterFacade::class, 'PrometheusExporter');
}
```

Add the endpoint in the routes/web.php (Lumen):
```
$app->get(
    'triadev/pe/metrics',
    \Triadev\PrometheusExporter\Controller\PrometheusExporterController::class . '@metrics'
);
```

Once installed you can now publish your config file and set your correct configuration for using the package.
```php
php artisan vendor:publish --provider="Triadev\PrometheusExporter\Provider\PrometheusExporterServiceProvider" --tag="config"
```

This will create a file ```config/prometheus-exporter.php```.

## Configuration
| Key        | Value           | Description  |
|:-------------:|:-------------:|:-----:|
| PROMETHEUS_ADAPTER | STRING | apc, redis or push |
| PROMETHEUS_REDIS_HOST | STRING | 127.0.0.1 |
| PROMETHEUS_REDIS_PORT | INTEGER | 6379 |
| PROMETHEUS_PUSH_GATEWAY_ADDRESS | STRING | Example: localhost:9091 |

## Reporting Issues
If you do find an issue, please feel free to report it with GitHub's bug tracker for this project.

Alternatively, fork the project and make a pull request. :)

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
