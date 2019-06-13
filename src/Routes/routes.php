<?php

Route::get(
    'triadev/pe/metrics',
    \Triadev\PrometheusExporter\Controller\LaravelController::class . '@metrics'
)->name('triadev.pe.metrics');
