<?php

use App\Models\Order;
use App\Models\User;
use App\Models\FoodItem;

test('admin can create manual order', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    
    $foodItem = FoodItem::create([
        'name' => 'Manual Test Food',
        'price' => 100,
        'stock_quantity' => 10,
        'image_url' => 'http://example.com/image.jpg'
    ]);

    $itemsJson = json_encode([$foodItem->id => 2]);

    $response = $this->actingAs($admin)->post('/orders/manual', [
        'order_type' => 'takeaway',
        'payment_method' => 'cash',
        'customer_name' => 'Walk-in Customer',
        'items' => $itemsJson,
    ]);

    $response->assertSessionHas('success');
    
    // Check DB
    $order = Order::where('user_name', 'Walk-in Customer')->first();
    $this->assertNotNull($order);
    $this->assertEquals('pending', $order->order_status);
    $this->assertEquals('takeaway', $order->order_type);
    
    // Check stock was deducted
    $foodItem->refresh();
    $this->assertEquals(8, $foodItem->stock_quantity);

    // Cleanup
    $order->delete();
    $foodItem->delete();
    $admin->delete();
});
