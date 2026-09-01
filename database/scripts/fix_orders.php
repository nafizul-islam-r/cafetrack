<?php

use App\Models\Order;

$orders = Order::all();

foreach ($orders as $order) {
    $items = $order->getRawOriginal('items');
    
    if (is_string($items)) {
        $decoded = json_decode($items, true);
        if (is_array($decoded)) {
            // Update the order in database with proper BSON array
            Order::where('_id', $order->id)->update(['items' => $decoded]);
        }
    }
}

echo "Order items casted to BSON arrays successfully.\n";
