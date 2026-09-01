<?php

namespace App\Http\Controllers;

use App\Models\FoodItem;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class FoodItemController extends Controller
{
    public function publicIndex()
    {
        if (\Illuminate\Support\Facades\Auth::check()) {
            return redirect()->route('food-items.index');
        }

        $foodItems = FoodItem::with('reviews')->get()->map(function ($item) {
            $item->reviews_count = $item->reviews->count();
            $item->reviews_avg_rating = $item->reviews->avg('rating');
            return $item;
        });

        return view('food-items.public-index', [
            'foodItems' => $foodItems
        ]);
    }
    public function index()
    {
        $foodItems = FoodItem::with('reviews')->get()->map(function ($item) {
            $item->reviews_count = $item->reviews->count();
            $item->reviews_avg_rating = $item->reviews->avg('rating');
            return $item;
        });

        $wishlistedItems = [];
        if (\Illuminate\Support\Facades\Auth::check()) {
            $wishlists = \App\Models\Wishlist::where('user_id', \Illuminate\Support\Facades\Auth::id())->get();
            foreach ($wishlists as $w) {
                $wishlistedItems[$w->food_item_id] = $w->id;
            }
        }

        return view('food-items.index', [
            'foodItems' => $foodItems,
            'wishlistedItems' => $wishlistedItems,
        ]);
    }

    public function create()
    {
        if (!Gate::allows('is-admin')) {
            abort(403);
        }
        return view('food-items.create');
    }

    public function store(Request $request)
    {
        if (!Gate::allows('is-admin')) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'image_url' => 'required|url|max:2048',
        ]);

        FoodItem::create([
            'name' => $validated['name'],
            'price' => (float) $validated['price'],
            'stock_quantity' => (int) $validated['stock_quantity'],
            'image_url' => $validated['image_url'],
        ]);

        return redirect()->route('food-items.index')->with('success', 'Food item added successfully!');
    }

    public function show(FoodItem $foodItem)
    {
        // We already load all reviews, so we can just calculate from that
        $reviews = $foodItem->reviews()->with('user')->orderBy('created_at', 'desc')->get();

        // Calculate aggregates
        $reviewCount = $reviews->count();
        $averageRating = $reviews->avg('rating');

        $canReview = false;
        if (\Illuminate\Support\Facades\Auth::check() && !\Illuminate\Support\Facades\Gate::allows('is-admin')) {
            $canReview = \App\Models\Order::where('user_id', \Illuminate\Support\Facades\Auth::id())
                ->where('order_status', 'completed')
                ->where('items.food_item_id', $foodItem->id)
                ->exists();
        }

        $inWishlist = false;
        $wishlistId = null;
        if (\Illuminate\Support\Facades\Auth::check()) {
            $wishlist = \App\Models\Wishlist::where('user_id', \Illuminate\Support\Facades\Auth::id())
                                              ->where('food_item_id', $foodItem->id)
                                              ->first();
            if ($wishlist) {
                $inWishlist = true;
                $wishlistId = $wishlist->id;
            }
        }

        return view('food-items.show', [
            'foodItem' => $foodItem,
            'reviews' => $reviews,
            'reviewCount' => $reviewCount,
            'averageRating' => $averageRating,
            'canReview' => $canReview,
            'inWishlist' => $inWishlist,
            'wishlistId' => $wishlistId,
        ]);
    }

    public function edit(FoodItem $foodItem)
    {
        if (!Gate::allows('is-admin')) {
            abort(403);
        }

        return view('food-items.edit', [
            'foodItem' => $foodItem
        ]);
    }

    public function update(Request $request, FoodItem $foodItem)
    {
        if (!Gate::allows('is-admin')) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'image_url' => 'required|url|max:2048',
        ]);

        $validated['price'] = (float) $validated['price'];
        $validated['stock_quantity'] = (int) $validated['stock_quantity'];

        $foodItem->update($validated);

        return redirect()->route('food-items.show', $foodItem)->with('success', 'Food item updated successfully!');
    }

    public function destroy(FoodItem $foodItem)
    {
        if (!Gate::allows('is-admin')) {
            abort(403);
        }

        $foodItem->delete();

        return redirect()->route('food-items.index')->with('success', 'Food item deleted successfully!');
    }
}
