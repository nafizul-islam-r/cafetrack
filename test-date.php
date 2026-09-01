<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$o = App\Models\Order::first();
echo gettype($o->getRawOriginal('created_at')) . " - " . print_r($o->getRawOriginal('created_at'), true) . "\n";
