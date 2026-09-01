<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\FoodItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            $wishlists = Wishlist::all()
                ->groupBy('food_item_id')
                ->map(function ($items) {
                    $first = $items->first();
                    $first->demand_count = $items->count();
                    return $first;
                })
                ->sortByDesc('demand_count')
                ->values();

            return view('wishlists.admin_index', [
                'wishlists' => $wishlists
            ]);
        }

        $wishlists = Wishlist::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('wishlists.index', [
            'wishlists' => $wishlists
        ]);
    }

    public function store(Request $request)
    {
        if (\Illuminate\Support\Facades\Gate::allows('is-admin')) {
            return redirect()->back()->with('error', 'Admins cannot add items to the wishlist.');
        }

        $validated = $request->validate([
            'food_item_id' => 'required|exists:food_items,id',
        ]);

        $foodItem = FoodItem::findOrFail($validated['food_item_id']);



        $existing = Wishlist::where('user_id', Auth::id())
                            ->where('food_item_id', $foodItem->id)
                            ->exists();

        if ($existing) {
            return redirect()->back()->with('error', 'Item is already in your wishlist.');
        }

        Wishlist::create([
            'user_id' => Auth::id(),
            'food_item_id' => $foodItem->id,
            'food_item_name' => $foodItem->name,
            'food_item_image_url' => $foodItem->image_url,
            'food_item_price' => $foodItem->price,
        ]);

        return redirect()->back()->with('success', 'Item added to wishlist!');
    }

    public function destroy(Wishlist $wishlist)
    {
        if ($wishlist->user_id !== Auth::id()) {
            abort(403);
        }

        $wishlist->delete();

        return redirect()->back()->with('success', 'Item removed from wishlist.');
    }
}
