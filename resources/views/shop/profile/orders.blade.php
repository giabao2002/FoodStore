<x-layouts.app title="Đơn hàng của tôi">
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
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                        <h1 class="text-xl font-bold">Đơn hàng của tôi</h1>

                        <div class="relative">
                            <select id="order-filter" class="appearance-none bg-gray-100 border border-gray-300 rounded-md pl-3 pr-10 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <option value="all">Tất cả đơn hàng</option>
                                <option value="pending">Đang xử lý</option>
                                <option value="processing">Đang giao hàng</option>
                                <option value="completed">Đã giao hàng</option>
                                <option value="cancelled">Đã hủy</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>

                    @if($orders->isEmpty())
                        <div class="p-8 text-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-shopping-bag text-gray-400 text-xl"></i>
                            </div>
                            <h2 class="text-xl font-bold mb-2">Chưa có đơn hàng nào</h2>
                            <p class="text-gray-600 mb-6">Bạn chưa có đơn hàng nào. Hãy tiếp tục mua sắm để đặt hàng.</p>
                            <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
                                <i class="fas fa-shopping-cart mr-2"></i> Mua sắm ngay
                            </a>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Mã đơn hàng
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Ngày đặt
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Sản phẩm
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tổng tiền
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Trạng thái
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Thao tác
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($orders as $order)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-blue-600">#{{ $order->order_number }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $order->created_at->format('d/m/Y') }}</div>
                                                <div class="text-sm text-gray-500">{{ $order->created_at->format('H:i') }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <div class="flex -space-x-2">
                                                        @foreach($order->orderItems->take(3) as $item)
                                                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="h-8 w-8 rounded-full ring-2 ring-white">
                                                        @endforeach

                                                        @if($order->orderItems->count() > 3)
                                                            <div class="h-8 w-8 rounded-full bg-gray-200 ring-2 ring-white flex items-center justify-center text-xs text-gray-600">
                                                                +{{ $order->orderItems->count() - 3 }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <span class="ml-2 text-sm text-gray-500">{{ $order->orderItems->count() }} sản phẩm</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ number_format($order->total) }}đ</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($order->status == 'pending')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Đang xử lý
                                                    </span>
                                                @elseif($order->status == 'processing')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                        Đang giao hàng
                                                    </span>
                                                @elseif($order->status == 'completed')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Đã giao hàng
                                                    </span>
                                                @elseif($order->status == 'cancelled')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        Đã hủy
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <a href="{{ route('profile.orders.detail', $order) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                                    Chi tiết
                                                </a>

                                                @if($order->status == 'pending')
                                                    <form action="{{ route('profile.orders.cancel', $order) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')">
                                                            Hủy
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="px-6 py-4 border-t border-gray-200">
                            {{ $orders->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
