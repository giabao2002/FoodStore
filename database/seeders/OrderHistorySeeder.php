<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderHistory;
use Illuminate\Database\Seeder;

class OrderHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all orders
        $orders = Order::all();

        foreach ($orders as $order) {
            // Create 'created' history for all orders
            OrderHistory::create([
                'order_id' => $order->id,
                'status' => 'created',
                'comment' => 'Đơn hàng đã được tạo',
                'created_at' => $order->created_at,
                'updated_at' => $order->created_at,
            ]);

            // Add additional history based on order status
            if ($order->status != 'pending') {
                OrderHistory::create([
                    'order_id' => $order->id,
                    'status' => 'pending',
                    'comment' => 'Đơn hàng đang chờ xử lý',
                    'created_at' => $order->created_at->addMinutes(rand(5, 30)),
                    'updated_at' => $order->created_at->addMinutes(rand(5, 30)),
                ]);
            }

            if ($order->status == 'processing' || $order->status == 'completed') {
                OrderHistory::create([
                    'order_id' => $order->id,
                    'status' => 'processing',
                    'comment' => 'Đơn hàng đang được giao',
                    'created_at' => $order->created_at->addHours(rand(1, 6)),
                    'updated_at' => $order->created_at->addHours(rand(1, 6)),
                ]);
            }

            if ($order->status == 'completed') {
                OrderHistory::create([
                    'order_id' => $order->id,
                    'status' => 'completed',
                    'comment' => 'Đơn hàng đã được giao thành công',
                    'created_at' => $order->created_at->addDays(rand(1, 3)),
                    'updated_at' => $order->created_at->addDays(rand(1, 3)),
                ]);
            }

            if ($order->status == 'cancelled') {
                OrderHistory::create([
                    'order_id' => $order->id,
                    'status' => 'cancelled',
                    'comment' => 'Đơn hàng đã bị hủy',
                    'created_at' => $order->created_at->addHours(rand(1, 24)),
                    'updated_at' => $order->created_at->addHours(rand(1, 24)),
                ]);
            }

            // Add payment status history if paid
            if ($order->payment_status) {
                OrderHistory::create([
                    'order_id' => $order->id,
                    'status' => 'payment_status_changed',
                    'comment' => 'Đơn hàng đã được thanh toán',
                    'data' => ['payment_status' => true],
                    'created_at' => $order->created_at->addMinutes(rand(10, 120)),
                    'updated_at' => $order->created_at->addMinutes(rand(10, 120)),
                ]);
            }
        }
    }
}
