<x-layouts.app title="Chi tiết đơn hàng #{{ $order->order_number }}">
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row md:space-x-6">
            <!-- Sidebar -->
            <div class="md:w-1/4 mb-6 md:mb-0">
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <div class="flex items-center border-b border-gray-200 pb-4 mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-blue-600"></i>
                        </div>
                        <div class="ml-4">
                            <h2 class="font-bold">{{ auth()->user()->name }}</h2>
                            <p class="text-sm text-gray-600">{{ auth()->user()->email }}</p>
                        </div>
                    </div>

                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('profile.edit') }}" class="block py-2 px-3 rounded-md hover:bg-gray-100">
                                <i class="fas fa-user-edit w-6"></i> Thông tin tài khoản
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('profile.orders') }}" class="block py-2 px-3 rounded-md bg-blue-100 text-blue-600">
                                <i class="fas fa-shopping-bag w-6"></i> Đơn hàng của tôi
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('profile.addresses') }}" class="block py-2 px-3 rounded-md hover:bg-gray-100">
                                <i class="fas fa-map-marker-alt w-6"></i> Sổ địa chỉ
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('profile.reviews') }}" class="block py-2 px-3 rounded-md hover:bg-gray-100">
                                <i class="fas fa-star w-6"></i> Đánh giá của tôi
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('profile.wishlist') }}" class="block py-2 px-3 rounded-md hover:bg-gray-100">
                                <i class="fas fa-heart w-6"></i> Sản phẩm yêu thích
                            </a>
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="block w-full text-left py-2 px-3 rounded-md hover:bg-gray-100 text-red-600">
                                    <i class="fas fa-sign-out-alt w-6"></i> Đăng xuất
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="md:w-3/4">
                <div class="mb-4">
                    <a href="{{ route('profile.orders') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                        <i class="fas fa-arrow-left mr-2"></i> Quay lại danh sách đơn hàng
                    </a>
                </div>

                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                        <h1 class="text-xl font-bold">Chi tiết đơn hàng #{{ $order->order_number }}</h1>

                        @if($order->status == 'pending')
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Đang xử lý
                            </span>
                        @elseif($order->status == 'processing')
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                Đang giao hàng
                            </span>
                        @elseif($order->status == 'completed')
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Đã giao hàng
                            </span>
                        @elseif($order->status == 'cancelled')
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Đã hủy
                            </span>
                        @endif
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="bg-gray-50 p-4 rounded-md">
                                <h2 class="font-bold text-lg mb-4">Thông tin đơn hàng</h2>
                                <dl class="space-y-2">
                                    <div class="flex justify-between">
                                        <dt class="text-gray-600">Mã đơn hàng:</dt>
                                        <dd class="font-medium">#{{ $order->order_number }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-gray-600">Ngày đặt hàng:</dt>
                                        <dd>{{ $order->created_at->format('d/m/Y H:i') }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-gray-600">Phương thức thanh toán:</dt>
                                        <dd>
                                            @if($order->payment_method == 'cod')
                                                Thanh toán khi nhận hàng
                                            @elseif($order->payment_method == 'bank')
                                                Chuyển khoản ngân hàng
                                            @elseif($order->payment_method == 'momo')
                                                Ví MoMo
                                            @endif
                                        </dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-gray-600">Trạng thái thanh toán:</dt>
                                        <dd>
                                            @if($order->payment_status == 1)
                                                <span class="text-green-600">Đã thanh toán</span>
                                            @else
                                                <span class="text-yellow-600">Chưa thanh toán</span>
                                            @endif
                                        </dd>
                                    </div>
                                </dl>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-md">
                                <h2 class="font-bold text-lg mb-4">Thông tin giao hàng</h2>
                                <dl class="space-y-2">
                                    <div>
                                        <dt class="text-gray-600">Người nhận:</dt>
                                        <dd class="font-medium">{{ $order->name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-gray-600">Địa chỉ:</dt>
                                        <dd>{{ $order->address }}, {{ $order->city }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-gray-600">Số điện thoại:</dt>
                                        <dd>{{ $order->phone }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-gray-600">Email:</dt>
                                        <dd>{{ $order->email }}</dd>
                                    </div>

                                    @if($order->note)
                                        <div>
                                            <dt class="text-gray-600">Ghi chú:</dt>
                                            <dd>{{ $order->note }}</dd>
                                        </div>
                                    @endif
                                </dl>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h2 class="font-bold text-lg mb-4">Các sản phẩm đã đặt</h2>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Sản phẩm
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Giá
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Số lượng
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Thành tiền
                                            </th>
                                            @if($order->status == 'completed')
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Đánh giá
                                                </th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($order->orderItems as $item)
                                            <tr>
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-10 w-10">
                                                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="h-10 w-10 rounded-md object-cover">
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900">
                                                                <a href="{{ route('products.show', $item->product) }}" class="hover:text-blue-600">
                                                                    {{ $item->product->name }}
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ number_format($item->price) }}đ</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $item->quantity }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ number_format($item->price * $item->quantity) }}đ</div>
                                                </td>
                                                @if($order->status == 'completed')
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        @if($item->reviewed)
                                                            <span class="text-green-600 text-sm">
                                                                <i class="fas fa-check-circle"></i> Đã đánh giá
                                                            </span>
                                                        @else
                                                            <a href="{{ route('products.show', ['product' => $item->product, 'review' => 'true']) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                                                Đánh giá
                                                            </a>
                                                        @endif
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-md">
                            <h2 class="font-bold text-lg mb-4">Tóm tắt thanh toán</h2>
                            <dl class="space-y-2">
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Tạm tính:</dt>
                                    <dd>{{ number_format($order->subtotal) }}đ</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Phí vận chuyển:</dt>
                                    <dd>{{ number_format($order->shipping_fee) }}đ</dd>
                                </div>

                                @if($order->discount > 0)
                                    <div class="flex justify-between text-green-600">
                                        <dt>Giảm giá:</dt>
                                        <dd>-{{ number_format($order->discount) }}đ</dd>
                                    </div>
                                @endif

                                <div class="flex justify-between border-t border-gray-200 pt-2 mt-2 font-bold">
                                    <dt>Tổng cộng:</dt>
                                    <dd class="text-red-600">{{ number_format($order->total) }}đ</dd>
                                </div>
                            </dl>
                        </div>

                        <div class="mt-6 flex justify-between">
                            <a href="{{ route('profile.orders') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-arrow-left mr-2"></i> Quay lại
                            </a>

                            @if($order->status == 'pending')
                                <form action="{{ route('profile.orders.cancel', $order) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700" onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')">
                                        <i class="fas fa-times mr-2"></i> Hủy đơn hàng
                                    </button>
                                </form>
                            @elseif($order->status == 'completed')
                                <a href="{{ route('orders.reorder', $order) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    <i class="fas fa-redo mr-2"></i> Đặt lại
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
