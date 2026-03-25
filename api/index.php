<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

// 1. Sesuaikan path ke vendor (tambah ../ karena sekarang ada di folder api)
require __DIR__.'/../vendor/autoload.php';

// 2. Sesuaikan path ke bootstrap (tambah ../)
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
