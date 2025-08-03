<x-layouts.app>
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <a href="{{ route('orders.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i> Quay lại danh sách đơn hàng
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
            <div class="px-6 py-4 bg-gray-100 border-b border-gray-200 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800">Chi tiết đơn hàng #{{ $order->order_number }}</h1>

                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                    @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                    @elseif($order->status === 'shipped') bg-indigo-100 text-indigo-800
                    @elseif($order->status === 'delivered') bg-green-100 text-green-800
                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                    @endif">
                    @if($order->status === 'pending') Đang chờ xử lý
                    @elseif($order->status === 'processing') Đang xử lý
                    @elseif($order->status === 'shipped') Đang giao hàng
                    @elseif($order->status === 'delivered') Đã giao hàng
                    @elseif($order->status === 'cancelled') Đã hủy
                    @endif
                </span>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800 mb-3">Thông tin đơn hàng</h2>
                        <div class="text-sm text-gray-700 space-y-2">
                            <p><span class="font-medium">Mã đơn hàng:</span> {{ $order->order_number }}</p>
                            <p><span class="font-medium">Ngày đặt hàng:</span> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                            <p><span class="font-medium">Phương thức thanh toán:</span>
                                @if($order->payment_method === 'cod') Thanh toán khi nhận hàng (COD)
                                @elseif($order->payment_method === 'bank_transfer') Chuyển khoản ngân hàng
                                @elseif($order->payment_method === 'momo') Ví MoMo
                                @else {{ $order->payment_method }}
                                @endif
                            </p>
                            <p><span class="font-medium">Trạng thái thanh toán:</span>
                                @if($order->payment_status === 'pending')
                                    <span class="text-yellow-600">Chờ xác nhận</span>
                                @elseif($order->payment_status === 'paid')
                                    <span class="text-green-600">Đã thanh toán</span>
                                @elseif($order->payment_status === 'failed')
                                    <span class="text-red-600">Thanh toán thất bại</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div>
                        <h2 class="text-lg font-semibold text-gray-800 mb-3">Thông tin giao hàng</h2>
                        <div class="text-sm text-gray-700 space-y-2">
                            <p><span class="font-medium">Người nhận:</span> {{ $order->shipping_name }}</p>
                            <p><span class="font-medium">Số điện thoại:</span> {{ $order->shipping_phone }}</p>
                            <p><span class="font-medium">Địa chỉ:</span> {{ $order->shipping_address }}</p>
                            <p><span class="font-medium">Ghi chú:</span> {{ $order->notes ?? 'Không có' }}</p>
                        </div>
                    </div>
                </div>

                <h2 class="text-lg font-semibold text-gray-800 mb-3 border-b pb-2">Chi tiết sản phẩm</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 mb-6">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sản phẩm</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Đơn giá</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số lượng</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-12 w-12">
                                                <img class="h-12 w-12 rounded-md object-cover" src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $item->product_name }}</div>
                                                @if($item->options)
                                                    <div class="text-xs text-gray-500">
                                                        @foreach(json_decode($item->options, true) ?? [] as $key => $value)
                                                            <span>{{ ucfirst($key) }}: {{ $value }}</span>
                                                            @if(!$loop->last), @endif
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ number_format($item->unit_price, 0, ',', '.') }}đ
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                        {{ number_format($item->unit_price * $item->quantity, 0, ',', '.') }}đ
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="border-t pt-4">
                    <div class="flex justify-end">
                        <div class="w-full md:w-1/3">
                            <div class="flex justify-between py-2 text-sm">
                                <span class="font-medium">Tạm tính:</span>
                                <span>{{ number_format($order->subtotal, 0, ',', '.') }}đ</span>
                            </div>

                            <div class="flex justify-between py-2 text-sm">
                                <span class="font-medium">Phí vận chuyển:</span>
                                <span>{{ number_format($order->shipping_fee, 0, ',', '.') }}đ</span>
                            </div>

                            @if($order->discount > 0)
                            <div class="flex justify-between py-2 text-sm text-green-600">
                                <span class="font-medium">Giảm giá:</span>
                                <span>-{{ number_format($order->discount, 0, ',', '.') }}đ</span>
                            </div>
                            @endif

                            <div class="flex justify-between py-2 text-lg font-bold">
                                <span>Tổng cộng:</span>
                                <span>{{ number_format($order->total_amount, 0, ',', '.') }}đ</span>
                            </div>
                        </div>
                    </div>
                </div>

                @if($order->status === 'pending')
                    <div class="mt-8 text-right">
                        <form action="{{ route('orders.cancel', $order) }}" method="POST" class="inline-block">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-md text-sm font-medium transition duration-300" onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')">
                                Hủy đơn hàng
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
