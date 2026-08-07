<?php

namespace App\Http\Controllers;

use App\Models\FoodItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class FoodItemController extends Controller
{
    public function publicIndex()
    {
        $foodItems = FoodItem::withAvg('reviews', 'rating')
                              ->withCount('reviews')
                              ->get();

        return view('food-items.public-index', [
            'foodItems' => $foodItems
        ]);
    }
    public function index()
    {
        $foodItems = FoodItem::withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->get();

        return view('food-items.index', [
            'foodItems' => $foodItems
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
            'price' => $validated['price'],
            'stock_quantity' => $validated['stock_quantity'],
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

        return view('food-items.show', [
            'foodItem' => $foodItem,
            'reviews' => $reviews,
            'reviewCount' => $reviewCount,
            'averageRating' => $averageRating,
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
