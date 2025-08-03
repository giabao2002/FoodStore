<x-layouts.app title="Đặt hàng thành công">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check text-2xl text-green-600"></i>
                </div>

                <h1 class="text-2xl font-bold text-gray-800 mb-2">Đặt hàng thành công!</h1>
                <p class="text-gray-600 mb-6">Cảm ơn bạn đã đặt hàng. Chúng tôi sẽ xử lý đơn hàng của bạn ngay lập tức.</p>

                <div class="bg-gray-50 p-4 rounded-md text-left mb-6">
                    <p class="font-medium mb-2">Mã đơn hàng: <span class="text-blue-600">#{{ $order->order_number }}</span></p>
                    <p class="text-sm text-gray-600">Một email xác nhận đã được gửi đến {{ $order->email }}</p>
                </div>

                <div class="border rounded-md mb-6">
                    <div class="border-b p-4">
                        <h2 class="font-bold text-lg mb-2">Thông tin đơn hàng</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-600 mb-1">Ngày đặt hàng:</p>
                                <p>{{ $order->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 mb-1">Phương thức thanh toán:</p>
                                <p>
                                    @if($order->payment_method == 'cod')
                                        Thanh toán khi nhận hàng
                                    @elseif($order->payment_method == 'bank')
                                        Chuyển khoản ngân hàng
                                    @elseif($order->payment_method == 'momo')
                                        Ví MoMo
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-600 mb-1">Tổng tiền:</p>
                                <p class="font-bold text-red-600">{{ number_format($order->total) }}đ</p>
                            </div>
                            <div>
                                <p class="text-gray-600 mb-1">Trạng thái:</p>
                                <p>
                                    <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded">
                                        Đang xử lý
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="border-b p-4">
                        <h2 class="font-bold text-lg mb-2">Thông tin giao hàng</h2>
                        <div class="text-sm">
                            <p class="mb-1"><span class="text-gray-600">Người nhận:</span> {{ $order->name }}</p>
                            <p class="mb-1"><span class="text-gray-600">Địa chỉ:</span> {{ $order->address }}, {{ $order->city }}</p>
                            <p class="mb-1"><span class="text-gray-600">Số điện thoại:</span> {{ $order->phone }}</p>
                            <p><span class="text-gray-600">Email:</span> {{ $order->email }}</p>

                            @if($order->note)
                                <p class="mt-2"><span class="text-gray-600">Ghi chú:</span> {{ $order->note }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="p-4">
                        <h2 class="font-bold text-lg mb-4">Sản phẩm đã đặt</h2>
                        <div class="space-y-4">
                            @foreach($order->orderItems as $item)
                                <div class="flex items-center">
                                    <div class="w-12 h-12 flex-shrink-0">
                                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover rounded">
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <h3 class="font-medium">{{ $item->product->name }}</h3>
                                        <p class="text-gray-500 text-sm">{{ $item->quantity }} x {{ number_format($item->price) }}đ</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-medium">{{ number_format($item->price * $item->quantity) }}đ</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Tạm tính:</span>
                                <span>{{ number_format($order->subtotal) }}đ</span>
                            </div>
                            <div class="flex justify-between items-center mt-2">
                                <span class="text-gray-600">Phí vận chuyển:</span>
                                <span>{{ number_format($order->shipping_fee) }}đ</span>
                            </div>

                            @if($order->discount > 0)
                                <div class="flex justify-between items-center mt-2 text-green-600">
                                    <span>Giảm giá:</span>
                                    <span>-{{ number_format($order->discount) }}đ</span>
                                </div>
                            @endif

                            <div class="flex justify-between items-center mt-2 pt-2 border-t border-gray-200 font-bold">
                                <span>Tổng cộng:</span>
                                <span class="text-red-600">{{ number_format($order->total) }}đ</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                    <a href="{{ route('profile.orders') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                        <i class="fas fa-list-alt mr-2"></i> Xem lịch sử đơn hàng
                    </a>
                    <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                        <i class="fas fa-shopping-bag mr-2"></i> Tiếp tục mua sắm
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
