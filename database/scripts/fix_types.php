<?php
require __DIR__.'/../../vendor/autoload.php';
$app = require_once __DIR__.'/../../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

foreach(\App\Models\FoodItem::all() as $item) {
    // Force raw update to bypass Eloquent dirty checks
    \Illuminate\Support\Facades\DB::table('food_items')
        ->where('_id', $item->_id)
        ->update([
            'stock_quantity' => (int) $item->stock_quantity,
            'price' => (float) $item->price
        ]);
    echo "Updated raw " . $item->name . PHP_EOL;
}
echo "Done.\n";
