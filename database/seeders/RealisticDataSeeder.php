<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FoodItem;
use App\Models\BoardGame;

class RealisticDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $foods = [
            ['name' => 'Club Sandwich', 'price' => 180, 'stock_quantity' => 20, 'image_url' => 'https://images.unsplash.com/photo-1528735602780-2552fd46c7af?w=500&q=80'],
            ['name' => 'Cold Coffee', 'price' => 120, 'stock_quantity' => 50, 'image_url' => 'https://images.unsplash.com/photo-1461023058943-0708e52235eb?w=500&q=80'],
            ['name' => 'Chicken Burger', 'price' => 220, 'stock_quantity' => 15, 'image_url' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=500&q=80'],
            ['name' => 'Chocolate Brownie', 'price' => 90, 'stock_quantity' => 30, 'image_url' => 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?w=500&q=80'],
            ['name' => 'French Fries', 'price' => 100, 'stock_quantity' => 40, 'image_url' => 'https://images.unsplash.com/photo-1576107232684-1279f390859f?w=500&q=80'],
        ];

        foreach ($foods as $food) {
            FoodItem::create($food);
        }

        $games = [
            ['name' => 'Catan', 'description' => 'A multiplayer board game where players build settlements.', 'status' => 'available', 'image_url' => 'https://images.unsplash.com/photo-1610890716171-6b1bb98ffaed?w=500&q=80'],
            ['name' => 'Monopoly', 'description' => 'Classic real estate trading board game.', 'status' => 'available', 'image_url' => 'https://images.unsplash.com/photo-1611891487122-2075782729a9?w=500&q=80'],
            ['name' => 'Chess Set', 'description' => 'Premium wooden chess set.', 'status' => 'available', 'image_url' => 'https://images.unsplash.com/photo-1586165368502-1bad197a6461?w=500&q=80'],
            ['name' => 'Uno Cards', 'description' => 'The classic card game of matching colors and numbers.', 'status' => 'available', 'image_url' => 'https://images.unsplash.com/photo-1609355153245-fbab1dbbe1a1?w=500&q=80'],
            ['name' => 'Jenga', 'description' => 'Classic block-stacking, stack-crashing game.', 'status' => 'available', 'image_url' => 'https://images.unsplash.com/photo-1587600373468-b80c3e387df0?w=500&q=80'],
        ];

        foreach ($games as $game) {
            BoardGame::create($game);
        }
    }
}
