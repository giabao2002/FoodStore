<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users and products
        $users = User::where('role', 'user')->get();
        $products = Product::all();

        // Create 50 orders
        for ($i = 0; $i < 50; $i++) {
            // Random user (can be null for guest checkout)
            $user = rand(0, 5) > 0 ? $users->random() : null;

            // Random status
            $statuses = ['pending', 'processing', 'completed', 'cancelled'];
            $statusWeights = [30, 25, 35, 10]; // Weights for each status
            $status = $this->getRandomWeighted($statuses, $statusWeights);

            // Random payment method
            $paymentMethods = ['cod', 'bank', 'momo'];
            $paymentMethodWeights = [50, 30, 20]; // Weights for each payment method
            $paymentMethod = $this->getRandomWeighted($paymentMethods, $paymentMethodWeights);

            // Random payment status (more likely to be paid if completed)
            $paymentStatus = false;
            if ($status == 'completed') {
                $paymentStatus = rand(0, 100) < 80; // 80% chance to be paid if completed
            } elseif ($status == 'processing') {
                $paymentStatus = rand(0, 100) < 50; // 50% chance to be paid if processing
            } elseif ($status == 'pending') {
                $paymentStatus = rand(0, 100) < 30; // 30% chance to be paid if pending
            }

            // If payment method is not COD, more likely to be paid
            if ($paymentMethod != 'cod') {
                $paymentStatus = rand(0, 100) < 70; // 70% chance to be paid if not COD
            }

            // Create order
            $orderNumber = 'ORD-' . strtoupper(Str::random(6));
            $subtotal = 0;
            $shippingFee = rand(15000, 30000);
            $discount = rand(0, 100) < 30 ? rand(10000, 50000) : 0; // 30% chance to have discount

            $order = Order::create([
                'user_id' => $user ? $user->id : null,
                'order_number' => $orderNumber,
                'status' => $status,
                'name' => $user ? $user->name : 'Khách vãng lai ' . rand(1, 100),
                'email' => $user ? $user->email : 'guest' . rand(1, 100) . '@example.com',
                'phone' => $user ? $user->phone : '09' . rand(10000000, 99999999),
                'address' => $user ? $user->address : 'Địa chỉ ngẫu nhiên ' . rand(1, 100),
                'city' => $user ? 'TP. Hồ Chí Minh' : 'TP. Hồ Chí Minh',
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentStatus,
                'subtotal' => 0, // Will update after adding items
                'shipping_fee' => $shippingFee,
                'discount' => $discount,
                'total' => 0, // Will update after adding items
                'note' => rand(0, 100) < 20 ? 'Ghi chú đơn hàng ' . rand(1, 100) : null, // 20% chance to have note
                'created_at' => now()->subDays(rand(1, 60))->subHours(rand(1, 24)),
            ]);

            // Add 1-5 random products to the order
            $numItems = rand(1, 5);
            $orderProducts = $products->random($numItems);

            foreach ($orderProducts as $product) {
                $quantity = rand(1, 3);
                $price = $product->price;
                $itemTotal = $price * $quantity;
                $subtotal += $itemTotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'price' => $price,
                    'quantity' => $quantity,
                ]);
            }

            // Update order totals
            $total = $subtotal + $shippingFee - $discount;
            $order->update([
                'subtotal' => $subtotal,
                'total' => max(0, $total),
            ]);
        }
    }

    /**
     * Get a random element from an array with weighted probability.
     */
    private function getRandomWeighted(array $items, array $weights)
    {
        $totalWeight = array_sum($weights);
        $randomWeight = rand(1, $totalWeight);
        $currentWeight = 0;

        foreach ($items as $index => $item) {
            $currentWeight += $weights[$index];
            if ($randomWeight <= $currentWeight) {
                return $item;
            }
        }

        return $items[0]; // Fallback to first item
    }
}
