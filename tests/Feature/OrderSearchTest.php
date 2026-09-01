<?php

use App\Models\Order;
use App\Models\User;

test('admin can search orders by token number', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    
    // Create an order with token
    $orderWithToken = Order::create([
        'order_number' => 'CT-99991',
        'user_id' => $admin->id,
        'user_name' => 'Test User',
        'order_type' => 'dine_in',
        'payment_method' => 'cash',
        'payment_status' => 'paid',
        'order_status' => 'pending',
        'token_number' => 888,
        'items' => [],
        'subtotal' => 0,
        'total' => 0,
    ]);

    $response = $this->actingAs($admin)->get('/orders?token=T-888');

    $response->assertStatus(200);
    $response->assertSee('T-888');
    
    $orderWithToken->delete(); // cleanup manually
});

test('admin can search orders by order number', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    
    // Create an order
    $order = Order::create([
        'order_number' => 'CT-99992',
        'user_id' => $admin->id,
        'user_name' => 'Test User',
        'order_type' => 'dine_in',
        'payment_method' => 'cash',
        'payment_status' => 'unpaid',
        'order_status' => 'pending',
        'token_number' => null,
        'items' => [],
        'subtotal' => 0,
        'total' => 0,
    ]);

    $response = $this->actingAs($admin)->get('/orders?token=CT-99992');

    $response->assertStatus(200);
    $response->assertSee('CT-99992');
    
    $order->delete(); // cleanup manually
});
