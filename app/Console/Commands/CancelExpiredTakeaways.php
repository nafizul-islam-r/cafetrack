<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\FoodItem;
use Carbon\Carbon;

class CancelExpiredTakeaways extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:cancel-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically cancel pending takeaway orders older than 30 minutes.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cutoffTime = Carbon::now()->subMinutes(30);

        $expiredOrders = Order::where('order_type', 'takeaway')
            ->where('order_status', 'pending')
            ->where('created_at', '<', $cutoffTime)
            ->get();

        $count = 0;
        foreach ($expiredOrders as $order) {
            // Restore stock
            foreach ($order->items as $item) {
                $foodItem = FoodItem::find($item['food_item_id']);
                if ($foodItem) {
                    $foodItem->increment('stock_quantity', (int) $item['quantity']);
                }
            }

            // Update statuses
            $updateData = ['order_status' => 'cancelled'];
            if ($order->payment_status === 'paid') {
                $updateData['payment_status'] = 'refunded';
            }

            $order->update($updateData);
            $count++;
        }

        $this->info("Cancelled $count expired takeaway orders.");
    }
}
