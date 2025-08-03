<x-layouts.app title="Thông tin tài khoản">
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
                            <a href="{{ route('profile.edit') }}" class="block py-2 px-3 rounded-md bg-blue-100 text-blue-600">
                                <i class="fas fa-user-edit w-6"></i> Thông tin tài khoản
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('profile.orders') }}" class="block py-2 px-3 rounded-md hover:bg-gray-100">
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
                    <div class="p-4 border-b border-gray-200">
                        <h1 class="text-xl font-bold">Thông tin tài khoản</h1>
                    </div>

                    <div class="p-6">
                        @if(session('profile_updated'))
                            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
                                <div class="flex items-center">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    <p>{{ session('profile_updated') }}</p>
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PATCH')

                            <div>
                                <label for="name" class="block mb-2 font-medium">Họ và tên</label>
                                <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block mb-2 font-medium">Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block mb-2 font-medium">Số điện thoại</label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <h2 class="text-lg font-medium mb-4">Đổi mật khẩu</h2>
                                <p class="text-sm text-gray-600 mb-4">Để giữ nguyên mật khẩu hiện tại, hãy để trống các trường bên dưới.</p>

                                <div class="space-y-4">
                                    <div>
                                        <label for="current_password" class="block mb-2 font-medium">Mật khẩu hiện tại</label>
                                        <input type="password" id="current_password" name="current_password"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        @error('current_password')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="password" class="block mb-2 font-medium">Mật khẩu mới</label>
                                        <input type="password" id="password" name="password"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        @error('password')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="password_confirmation" class="block mb-2 font-medium">Xác nhận mật khẩu mới</label>
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-gray-200">
                                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md">
                                    Cập nhật thông tin
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
