<?php

namespace App\Http\Controllers;

use App\Models\FoodItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class FoodItemController extends Controller
{
    public function index()
    {
        $foodItems = FoodItem::withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->get();

        return view('food.index', [
            'foodItems' => $foodItems
        ]);
    }

    public function create()
    {
        if (!Gate::allows('is-admin')) {
            abort(403);
        }
        return view('food.create');
    }

    public function store(Request $request)
    {
        if (!Gate::allows('is-admin')) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = $request->file('image')->store('food-images', 'public');

        FoodItem::create([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'quantity' => $validated['quantity'],
            'image_url' => $imagePath,
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

        return view('food.show', [
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

        return view('food.edit', [
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
            'quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Image is optional
        ]);

        // Check if a new image was uploaded
        if ($request->hasFile('image')) {
            // Delete the old image
            Storage::disk('public')->delete($foodItem->image_url);

            // Store the new image and update the path
            $validated['image_url'] = $request->file('image')->store('food-images', 'public');
        }

        $foodItem->update($validated);

        return redirect()->route('food.show', $foodItem)->with('success', 'Food item updated successfully!');
    }

    public function destroy(FoodItem $foodItem)
    {
        if (!Gate::allows('is-admin')) {
            abort(403);
        }

        // Delete the associated image file
        Storage::disk('public')->delete($foodItem->image_url);

        $foodItem->delete();

        return redirect()->route('food-items.index')->with('success', 'Food item deleted successfully!');
    }
}
