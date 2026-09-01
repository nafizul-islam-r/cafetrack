<?php

use App\Models\User;
use App\Models\FoodItem;
use App\Models\Wishlist;

test('user can add in-stock items to wishlist', function () {
    $user = User::factory()->create(['student_id' => 'USER888']);
    
    $foodItem = FoodItem::create([
        'name' => 'Wishlist Test Food',
        'price' => 50,
        'stock_quantity' => 10,
        'image_url' => 'http://example.com/image.jpg'
    ]);

    $response = $this->actingAs($user)->post('/wishlists', [
        'food_item_id' => $foodItem->id,
    ]);

    $response->assertSessionHas('success', 'Item added to wishlist!');
    
    $wishlist = Wishlist::where('user_id', $user->id)->first();
    $this->assertNotNull($wishlist);
    
    // Cleanup
    $wishlist->delete();
    $foodItem->delete();
    $user->delete();
});

test('wishlists are not deleted when stock is updated', function () {
    $admin = User::factory()->create(['role' => 'admin', 'student_id' => 'ADMIN999']);
    $user = User::factory()->create(['student_id' => 'USER999']);
    
    $foodItem = FoodItem::create([
        'name' => 'Restock Test Food',
        'price' => 50,
        'stock_quantity' => 0,
        'image_url' => 'http://example.com/image.jpg'
    ]);

    $wishlist = Wishlist::create([
        'user_id' => $user->id,
        'food_item_id' => $foodItem->id,
        'food_item_name' => $foodItem->name,
        'food_item_price' => $foodItem->price,
        'food_item_image_url' => $foodItem->image_url,
    ]);

    // Admin updates stock
    $response = $this->actingAs($admin)->put('/food-items/' . $foodItem->id, [
        'name' => $foodItem->name,
        'price' => $foodItem->price,
        'stock_quantity' => 10, // Restocked!
        'image_url' => $foodItem->image_url,
    ]);

    $response->assertSessionHas('success');
    
    // Check wishlist still exists
    $wishlistCheck = Wishlist::where('user_id', $user->id)->first();
    $this->assertNotNull($wishlistCheck);
    
    // Cleanup
    $wishlistCheck->delete();
    $foodItem->delete();
    $user->delete();
    $admin->delete();
});
