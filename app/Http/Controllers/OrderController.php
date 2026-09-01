<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\FoodItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // Dynamically run the expiration command to ensure local tests show correct status
        \Illuminate\Support\Facades\Artisan::call('orders:cancel-expired');

        $query = Order::query();

        if (Gate::allows('is-admin')) {
            if ($request->filled('order_number')) {
                $query->where('order_number', 'like', '%' . $request->query('order_number') . '%');
            }
            
            if ($request->filled('token')) {
                $tokenNumber = (int) preg_replace('/[^0-9]/', '', $request->query('token'));
                if ($tokenNumber > 0) {
                    $query->where('token_number', $tokenNumber);
                }
            }

            $status = $request->query('status');
            if ($status) {
                $query->where('order_status', $status);
            }
        } else {
            $query->where('user_id', Auth::id());
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        return view('orders.index', [
            'orders' => $orders
        ]);
    }

    public function create()
    {
        if (!Gate::allows('is-admin')) {
            abort(403);
        }

        $foodItems = FoodItem::where('stock_quantity', '>', 0)->get();
        return view('orders.create', ['foodItems' => $foodItems]);
    }

    public function storeManual(Request $request)
    {
        if (!Gate::allows('is-admin')) {
            abort(403);
        }

        $validated = $request->validate([
            'order_type' => 'required|in:dine_in,takeaway',
            'payment_method' => 'required|in:cash,bkash',
            'customer_name' => 'nullable|string|max:255',
            'items' => 'required|string', // JSON string of items
        ]);

        $itemsData = json_decode($validated['items'], true);
        if (empty($itemsData)) {
            return redirect()->back()->with('error', 'No items selected.');
        }

        $subtotal = 0;
        $items = [];
        foreach ($itemsData as $id => $quantity) {
            $foodItem = FoodItem::find($id);
            if ($foodItem && $foodItem->stock_quantity >= $quantity) {
                $subtotal += $foodItem->price * $quantity;
                $items[] = [
                    'food_item_id' => $foodItem->id,
                    'name' => $foodItem->name,
                    'price' => $foodItem->price,
                    'quantity' => $quantity,
                    'image_url' => $foodItem->image_url,
                ];
                $foodItem->decrement('stock_quantity', (int) $quantity);
            }
        }

        if (empty($items)) {
            return redirect()->back()->with('error', 'Selected items are out of stock or invalid.');
        }

        $orderNumber = 'CT-' . str_pad((Order::count() + 1), 5, '0', STR_PAD_LEFT);
        
        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => Auth::id(), // Admin's ID
            'user_name' => !empty($validated['customer_name']) ? $validated['customer_name'] : 'Guest',
            'user_email' => null,
            'user_student_id' => null,
            'order_type' => $validated['order_type'],
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'paid', // Manual orders usually paid at counter
            'order_status' => 'pending', // pending so they can track token
            'token_number' => (Order::whereNotNull('token_number')->max('token_number') ?? 0) + 1,
            'bkash_transaction_id' => null,
            'items' => $items,
            'subtotal' => $subtotal,
            'total' => $subtotal,
        ]);

        return redirect()->route('orders.show', $order)->with('success', 'Manual order created successfully!');
    }

    public function show(Order $order)
    {
        // Dynamically run the expiration command
        \Illuminate\Support\Facades\Artisan::call('orders:cancel-expired');
        // Refresh the model in case it was just cancelled
        $order->refresh();

        if (!Gate::allows('is-admin') && $order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('orders.show', [
            'order' => $order
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_type' => 'required|in:dine_in,takeaway',
            'payment_method' => 'required|in:cash,bkash',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $user = Auth::user();
        
        $subtotal = 0;
        $items = [];
        foreach ($cart as $id => $details) {
            $subtotal += $details['price'] * $details['quantity'];
            $items[] = [
                'food_item_id' => $id,
                'name' => $details['name'],
                'price' => $details['price'],
                'quantity' => $details['quantity'],
                'image_url' => $details['image_url'],
            ];
        }

        $orderNumber = 'CT-' . str_pad((Order::count() + 1), 5, '0', STR_PAD_LEFT);

        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'user_student_id' => $user->student_id,
            'order_type' => $validated['order_type'],
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'unpaid',
            'order_status' => 'pending',
            'token_number' => null, // Set below if paid
            'bkash_transaction_id' => null,
            'items' => $items,
            'subtotal' => $subtotal,
            'total' => $subtotal,
        ]);

        // Decrease stock immediately upon order placement
        foreach ($items as $item) {
            $foodItem = FoodItem::find($item['food_item_id']);
            if ($foodItem) {
                $foodItem->decrement('stock_quantity', (int) $item['quantity']);
            }
        }

        session()->forget('cart');

        if ($validated['payment_method'] === 'bkash') {
            return view('bkash.payment', ['order' => $order]);
        }

        return redirect()->route('orders.show', $order)->with('success', 'Order placed successfully! Please pay at the counter.');
    }

    public function markPaid(Order $order)
    {
        if (!Gate::allows('is-admin') && $order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->order_status === 'cancelled') {
            return redirect()->route('orders.show', $order)->with('error', 'Cannot modify a cancelled order.');
        }

        if ($order->payment_status === 'paid') {
            return redirect()->route('orders.show', $order)->with('info', 'Order is already marked as paid.');
        }

        // Generate token logic (find max token)
        $maxToken = Order::whereNotNull('token_number')->max('token_number') ?? 0;
        $nextToken = $maxToken + 1;

        $order->update([
            'payment_status' => 'paid',
            'token_number' => $nextToken,
        ]);



        return redirect()->route('orders.show', $order)->with('success', 'Order marked as paid. Token: T-' . str_pad($nextToken, 3, '0', STR_PAD_LEFT));
    }

    public function markCompleted(Order $order)
    {
        if (!Gate::allows('is-admin')) {
            abort(403);
        }

        if ($order->order_status === 'cancelled') {
            return redirect()->route('orders.show', $order)->with('error', 'Cannot modify a cancelled order.');
        }

        $order->update([
            'order_status' => 'completed',
        ]);

        return redirect()->route('orders.show', $order)->with('success', 'Order marked as completed.');
    }

    public function cancel(Order $order)
    {
        if (!Gate::allows('is-admin')) {
            abort(403);
        }

        if ($order->order_status === 'cancelled') {
            return redirect()->route('orders.show', $order)->with('info', 'Order is already cancelled.');
        }

        // Restore stock for all orders since it's deducted at placement
        foreach ($order->items as $item) {
            $foodItem = FoodItem::find($item['food_item_id']);
            if ($foodItem) {
                $foodItem->increment('stock_quantity', (int) $item['quantity']);
            }
        }

        $order->update([
            'order_status' => 'cancelled',
        ]);

        return redirect()->route('orders.show', $order)->with('success', 'Order cancelled successfully.');
    }
}
