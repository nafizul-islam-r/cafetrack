<?php
require __DIR__.'/../../vendor/autoload.php';
$app = require_once __DIR__.'/../../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$order = \App\Models\Order::latest()->first();
if ($order) {
    echo gettype($order->items) . PHP_EOL;
    foreach ($order->items as $item) {
        echo gettype($item) . PHP_EOL;
        if (is_array($item) || $item instanceof \ArrayAccess) {
            echo "ID: " . $item['food_item_id'] . PHP_EOL;
        } else if (is_object($item)) {
            echo "ID: " . $item->food_item_id . PHP_EOL;
        }
    }
}
