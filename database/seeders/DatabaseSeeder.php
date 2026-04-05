<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo tài khoản admin mẫu
        \App\Models\User::factory()->create([
            'name'  => 'Nguyễn Công Sơn',
            'email' => 'admin@example.com',
        ]);

        // ── Seed danh mục ────────────────────────────────────────────────────
        $categories = [
            ['name' => 'Điện thoại',    'description' => 'Điện thoại thông minh các loại', 'is_visible' => true],
            ['name' => 'Laptop',         'description' => 'Máy tính xách tay',               'is_visible' => true],
            ['name' => 'Phụ kiện',       'description' => 'Phụ kiện điện tử',                'is_visible' => true],
            ['name' => 'Màn hình',       'description' => 'Màn hình máy tính',               'is_visible' => false],
        ];

        $createdCategories = [];
        foreach ($categories as $cat) {
            $createdCategories[] = Category::create($cat);
        }

        // ── Seed sản phẩm ────────────────────────────────────────────────────
        $products = [
            [
                'category_id'      => $createdCategories[0]->id,
                'name'             => 'iPhone 15 Pro Max',
                'description'      => '<p>Điện thoại cao cấp nhất của Apple với chip A17 Pro.</p>',
                'price'            => 33990000,
                'stock_quantity'   => 50,
                'status'           => 'published',
                'discount_percent' => 5,
            ],
            [
                'category_id'      => $createdCategories[0]->id,
                'name'             => 'Samsung Galaxy S24 Ultra',
                'description'      => '<p>Flagship của Samsung với bút S-Pen tích hợp.</p>',
                'price'            => 31990000,
                'stock_quantity'   => 30,
                'status'           => 'published',
                'discount_percent' => 10,
            ],
            [
                'category_id'      => $createdCategories[1]->id,
                'name'             => 'MacBook Pro M3 14 inch',
                'description'      => '<p>Hiệu năng vượt trội với chip Apple M3.</p>',
                'price'            => 52990000,
                'stock_quantity'   => 15,
                'status'           => 'published',
                'discount_percent' => 0,
            ],
            [
                'category_id'      => $createdCategories[1]->id,
                'name'             => 'Dell XPS 15',
                'description'      => '<p>Laptop Windows cao cấp với màn hình OLED.</p>',
                'price'            => 45000000,
                'stock_quantity'   => 0,
                'status'           => 'out_of_stock',
                'discount_percent' => 15,
            ],
            [
                'category_id'      => $createdCategories[2]->id,
                'name'             => 'AirPods Pro 2',
                'description'      => '<p>Tai nghe không dây chống ồn chủ động.</p>',
                'price'            => 6490000,
                'stock_quantity'   => 100,
                'status'           => 'published',
                'discount_percent' => 0,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
