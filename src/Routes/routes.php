<?php

Route::get(
    'triadev/pe/metrics',
    \Triadev\PrometheusExporter\Controller\PrometheusExporterController::class . '@metrics'
)->name('triadev.pe.metrics');
