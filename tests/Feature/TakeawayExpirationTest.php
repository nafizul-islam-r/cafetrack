<?php

use App\Models\Order;
use App\Models\User;
use App\Models\FoodItem;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

test('expired pending takeaway orders are cancelled and stock is restored', function () {
    // 1. Create a food item
    $foodItem = FoodItem::create([
        'name' => 'Expired Test Food',
        'price' => 50,
        'stock_quantity' => 10,
        'image_url' => 'http://example.com/image.jpg'
    ]);

    // 2. Create an order manually that simulates being created 35 mins ago
    $order = Order::create([
        'order_number' => 'CT-EXPIRE1',
        'user_id' => null,
        'user_name' => 'John Expire',
        'order_type' => 'takeaway',
        'payment_method' => 'bkash',
        'payment_status' => 'paid',
        'order_status' => 'pending',
        'items' => [
            [
                'food_item_id' => $foodItem->id,
                'name' => $foodItem->name,
                'price' => $foodItem->price,
                'quantity' => 2,
                'image_url' => $foodItem->image_url,
            ]
        ],
        'total' => 100,
    ]);
    
    // Explicitly set created_at bypassing the create method override
    $order->created_at = Carbon::now()->subMinutes(35);
    $order->save(['timestamps' => false]);
    
    // Deduct stock (since OrderController normally does this)
    $foodItem->decrement('stock_quantity', 2);
    $foodItem->refresh();
    $this->assertEquals(8, $foodItem->stock_quantity);

    // 3. Run the scheduled command
    Artisan::call('orders:cancel-expired');

    // 4. Assert order was updated
    $order->refresh();
    $this->assertEquals('cancelled', $order->order_status);
    $this->assertEquals('refunded', $order->payment_status);

    // 5. Assert stock was restored
    $foodItem->refresh();
    $this->assertEquals(10, $foodItem->stock_quantity);
    
    // Cleanup
    $order->delete();
    $foodItem->delete();
});
