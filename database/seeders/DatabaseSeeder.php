<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tạo tài khoản admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'phone' => '0123456789',
            'address' => 'Địa chỉ của Admin',
            'role' => 'admin',
        ]);

        // Tạo tài khoản người dùng
        User::create([
            'name' => 'Người dùng',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'phone' => '0987654321',
            'address' => 'Địa chỉ của Người dùng',
            'role' => 'user',
        ]);

        // Tạo các danh mục
        $categories = [
            [
                'name' => 'Món chính',
                'slug' => 'mon-chinh',
                'description' => 'Các món ăn chính trong bữa ăn',
            ],
            [
                'name' => 'Món khai vị',
                'slug' => 'mon-khai-vi',
                'description' => 'Các món ăn nhẹ trước bữa ăn chính',
            ],
            [
                'name' => 'Món tráng miệng',
                'slug' => 'mon-trang-mieng',
                'description' => 'Các món ngọt sau bữa ăn',
            ],
            [
                'name' => 'Đồ uống',
                'slug' => 'do-uong',
                'description' => 'Các loại nước uống',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Tạo các sản phẩm mẫu
        $products = [
            // Món chính
            [
                'name' => 'Cơm rang dương châu',
                'slug' => 'com-rang-duong-chau',
                'description' => 'Cơm rang dương châu với nhiều loại thịt và rau củ',
                'price' => 50000,
                'stock' => 100,
                'category_id' => 1,
            ],
            [
                'name' => 'Phở bò',
                'slug' => 'pho-bo',
                'description' => 'Phở bò truyền thống với nước dùng ngon',
                'price' => 45000,
                'stock' => 100,
                'category_id' => 1,
            ],
            [
                'name' => 'Bún bò Huế',
                'slug' => 'bun-bo-hue',
                'description' => 'Bún bò Huế cay nồng đặc trưng',
                'price' => 55000,
                'stock' => 100,
                'category_id' => 1,
            ],

            // Món khai vị
            [
                'name' => 'Gỏi cuốn',
                'slug' => 'goi-cuon',
                'description' => 'Gỏi cuốn tôm thịt với rau sống và bún',
                'price' => 35000,
                'stock' => 100,
                'category_id' => 2,
            ],
            [
                'name' => 'Chả giò',
                'slug' => 'cha-gio',
                'description' => 'Chả giò giòn rụm với nhân thịt và nấm',
                'price' => 40000,
                'stock' => 100,
                'category_id' => 2,
            ],

            // Món tráng miệng
            [
                'name' => 'Chè thái',
                'slug' => 'che-thai',
                'description' => 'Chè thái ngọt mát với nhiều loại trái cây',
                'price' => 25000,
                'stock' => 100,
                'category_id' => 3,
            ],
            [
                'name' => 'Bánh flan',
                'slug' => 'banh-flan',
                'description' => 'Bánh flan mềm mịn với caramel',
                'price' => 20000,
                'stock' => 100,
                'category_id' => 3,
            ],

            // Đồ uống
            [
                'name' => 'Trà đào',
                'slug' => 'tra-dao',
                'description' => 'Trà đào thanh mát với đào tươi',
                'price' => 30000,
                'stock' => 100,
                'category_id' => 4,
            ],
            [
                'name' => 'Cà phê sữa đá',
                'slug' => 'ca-phe-sua-da',
                'description' => 'Cà phê sữa đá đậm đà',
                'price' => 25000,
                'stock' => 100,
                'category_id' => 4,
            ],
        ];
        foreach ($products as $product) {
            Product::create($product);
        }

        // Seed orders
        $this->call([
            OrderSeeder::class,
            OrderHistorySeeder::class,
        ]);
    }
}
