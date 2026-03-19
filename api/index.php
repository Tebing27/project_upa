<?php

// 1. Sesuaikan path ke vendor (tambah ../ karena sekarang ada di folder api)
require __DIR__ . '/../vendor/autoload.php';

// 2. Sesuaikan path ke bootstrap (tambah ../)
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
)->send();

$kernel->terminate($request, $response);