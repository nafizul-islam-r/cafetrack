<?php

use App\Models\FoodItem;

$items = FoodItem::all();

foreach ($items as $item) {
    FoodItem::where('_id', $item->id)->update([
        'stock_quantity' => (int) $item->stock_quantity,
        'price' => (float) $item->price
    ]);
}

echo "Database fields casted successfully via Query Builder.\n";
