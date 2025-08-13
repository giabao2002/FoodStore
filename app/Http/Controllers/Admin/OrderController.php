<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Hiển thị danh sách đơn hàng
     */
    public function index(Request $request)
    {
        $query = Order::with('user');

        // Lọc theo trạng thái
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Tìm kiếm theo mã đơn hàng
        if ($request->has('search')) {
            $query->where('order_number', 'like', "%{$request->search}%");
        }

        // Lọc theo thời gian
        if ($request->has('from_date') && $request->has('to_date')) {
            $query->whereBetween('created_at', [$request->from_date . ' 00:00:00', $request->to_date . ' 23:59:59']);
        }

        $orders = $query->latest()->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Hiển thị chi tiết đơn hàng
     */
    public function show(Order $order)
    {
        $order->load('user', 'orderItems.product');
        $orderHistory = $order->orderHistories()->with('user')->latest()->get();
        return view('admin.orders.show', compact('order', 'orderHistory'));
    }

    /**
     * Cập nhật trạng thái đơn hàng
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Bắt đầu transaction
        DB::beginTransaction();

        try {
            // Cập nhật trạng thái đơn hàng
            $order->status = $newStatus;
            $order->save();

            // Nếu đơn hàng bị hủy, hoàn lại số lượng tồn kho
            if ($oldStatus != 'cancelled' && $newStatus == 'cancelled') {
                foreach ($order->orderItems as $item) {
                    $product = $item->product;
                    $product->stock += $item->quantity;
                    $product->save();
                }
            }

            // Nếu đơn hàng từ hủy sang trạng thái khác, trừ lại số lượng tồn kho
            if ($oldStatus == 'cancelled' && $newStatus != 'cancelled') {
                foreach ($order->orderItems as $item) {
                    $product = $item->product;

                    // Kiểm tra xem còn đủ số lượng tồn kho không
                    if ($product->stock < $item->quantity) {
                        throw new \Exception("Sản phẩm {$product->name} không đủ số lượng tồn kho.");
                    }

                    $product->stock -= $item->quantity;
                    $product->save();
                }
            }

            DB::commit();

            return redirect()->route('admin.orders.show', $order)->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Hiển thị form chỉnh sửa đơn hàng
     */
    public function edit(Order $order)
    {
        $order->load('orderItems.product');
        return view('admin.orders.edit', compact('order'));
    }

    /**
     * Cập nhật thông tin đơn hàng
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
            'payment_status' => 'required|in:0,1',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'subtotal' => 'required|numeric|min:0',
            'shipping_fee' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'items' => 'required|array',
            'items.*.id' => 'required|exists:order_items,id',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Bắt đầu transaction
        DB::beginTransaction();

        try {
            // Cập nhật thông tin đơn hàng
            $order->update([
                'status' => $request->status,
                'payment_status' => $request->payment_status,
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
                'city' => $request->city,
                'subtotal' => $request->subtotal,
                'shipping_fee' => $request->shipping_fee,
                'total' => $request->total,
            ]);

            // Cập nhật chi tiết đơn hàng
            foreach ($request->items as $itemData) {
                $orderItem = OrderItem::find($itemData['id']);

                if ($orderItem && $orderItem->order_id == $order->id) {
                    $product = Product::find($itemData['product_id']);
                    $orderItem->update([
                        'price' => $itemData['price'],
                        'quantity' => $itemData['quantity'],
                        'product_name' => $product ? $product->name : null,
                    ]);
                }
            }

            // Tạo lịch sử đơn hàng
            $order->orderHistories()->create([
                'user_id' => $request->user()->id,
                'status' => 'updated',
                'comment' => 'Đơn hàng đã được cập nhật bởi quản trị viên',
            ]);

            DB::commit();

            return redirect()->route('admin.orders.show', $order)->with('success', 'Cập nhật đơn hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
}
