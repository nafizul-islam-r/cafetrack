<?php

namespace App\Http\Controllers;

use App\Models\FoodItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct()
    {
        if (\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Gate::allows('is-admin')) {
            abort(403, 'Admins cannot use the cart.');
        }
    }

    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', ['cart' => $cart]);
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'food_item_id' => 'required|exists:food_items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $foodItem = FoodItem::findOrFail($validated['food_item_id']);

        if ($foodItem->stock_quantity < $validated['quantity']) {
            return redirect()->back()->with('error', 'Not enough stock available.');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$foodItem->id])) {
            $newQuantity = $cart[$foodItem->id]['quantity'] + $validated['quantity'];
            if ($newQuantity > $foodItem->stock_quantity) {
                return redirect()->back()->with('error', 'Cannot add more. Exceeds available stock.');
            }
            $cart[$foodItem->id]['quantity'] = $newQuantity;
        } else {
            $cart[$foodItem->id] = [
                "name" => $foodItem->name,
                "quantity" => $validated['quantity'],
                "price" => $foodItem->price,
                "image_url" => $foodItem->image_url,
                "stock_quantity" => $foodItem->stock_quantity
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Item added to cart!');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'food_item_id' => 'required|exists:food_items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);
        $foodItem = FoodItem::findOrFail($validated['food_item_id']);

        if (isset($cart[$validated['food_item_id']])) {
            if ($validated['quantity'] > $foodItem->stock_quantity) {
                return redirect()->back()->with('error', 'Cannot exceed available stock.');
            }
            $cart[$validated['food_item_id']]['quantity'] = $validated['quantity'];
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Cart updated!');
    }

    public function remove(Request $request)
    {
        $validated = $request->validate([
            'food_item_id' => 'required',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$validated['food_item_id']])) {
            unset($cart[$validated['food_item_id']]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Item removed from cart!');
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }
        
        return view('cart.checkout', ['cart' => $cart]);
    }
}
