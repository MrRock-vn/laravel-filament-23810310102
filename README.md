# 🛒 Hệ Thống Quản Trị Sản Phẩm – Laravel Filament

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-v12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white"/>
  <img src="https://img.shields.io/badge/Filament-v3.3-4F46E5?style=for-the-badge"/>
  <img src="https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white"/>
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white"/>
</p>

---

## 👤 Thông tin sinh viên

| Thông tin | Chi tiết |
|-----------|----------|
| **Họ và tên** | Nguyễn Công Sơn |
| **MSSV** | 23810310102 |
| **Môn học** | Xây dựng hệ thống quản trị với Laravel Filament |
| **Giảng viên** | Cấn Đức Điệp |

---

## 📋 Mô tả dự án

Hệ thống Admin Panel được xây dựng bằng **Laravel 12** kết hợp **Filament v3** để quản lý danh mục và sản phẩm thương mại điện tử. Giao diện trực quan, hỗ trợ đầy đủ chức năng CRUD, tìm kiếm, lọc dữ liệu và upload hình ảnh.

---

## 🗄️ Cấu trúc Database

> ✅ Tất cả tên bảng đều bắt đầu bằng MSSV `23810310102`

### Bảng `23810310102_categories`
| Cột | Kiểu | Mô tả |
|-----|------|-------|
| `id` | bigint (PK) | Khóa chính |
| `name` | varchar (unique) | Tên danh mục |
| `slug` | varchar (unique) | Slug tự động tạo |
| `description` | text | Mô tả |
| `is_visible` | boolean | Trạng thái hiển thị |
| `created_at / updated_at` | timestamp | Thời gian |

### Bảng `23810310102_products`
| Cột | Kiểu | Mô tả |
|-----|------|-------|
| `id` | bigint (PK) | Khóa chính |
| `category_id` | bigint (FK) | Khóa ngoại → categories |
| `name` | varchar | Tên sản phẩm |
| `slug` | varchar (unique) | Slug tự động tạo |
| `description` | longtext | Mô tả (Rich Text) |
| `price` | bigint | Giá (VNĐ) |
| `stock_quantity` | int | Số lượng tồn kho |
| `image_path` | varchar | Đường dẫn ảnh |
| `status` | enum | draft / published / out_of_stock |
| `discount_percent` | tinyint | 🎁 Phần trăm giảm giá (0–100%) |
| `created_at / updated_at` | timestamp | Thời gian |

---

## ✨ Tính năng đã thực hiện

### 📁 Quản lý Danh mục (CategoryResource)
- ✅ Slug URL: `/admin/23810310102-categories`
- ✅ Tự động tạo **slug** từ `name` khi nhập liệu
- ✅ Bảng danh sách với **bộ lọc `is_visible`** (TernaryFilter)
- ✅ Hiển thị số lượng sản phẩm thuộc từng danh mục
- ✅ Tìm kiếm theo tên
- ✅ CRUD đầy đủ (Thêm / Sửa / Xóa)

### 🛍️ Quản lý Sản phẩm (ProductResource)
- ✅ Slug URL: `/admin/23810310102-products`
- ✅ **Grid layout 3 cột** hiện đại
- ✅ **Rich Editor** cho phần mô tả sản phẩm
- ✅ **Upload ảnh đại diện** (JPG, PNG, WebP – tối đa 2MB)
- ✅ **Hiển thị giá VNĐ** định dạng `1.000.000 ₫`
- ✅ **Tìm kiếm theo tên** sản phẩm
- ✅ **Lọc theo danh mục** và trạng thái
- ✅ Validation: giá không âm, tồn kho là số nguyên

### 🎁 Trường sáng tạo: `discount_percent`
- Phần trăm giảm giá từ **0% đến 100%**
- Validation chặt chẽ: số nguyên, min 0, max 100
- Hiển thị **badge đỏ** trong bảng khi đang giảm giá
- Accessor `getDiscountedPriceAttribute()` tính giá thực tự động

### 🎨 Màu chủ đạo tùy chỉnh
```php
// app/Providers/Filament/AdminPanelProvider.php
->colors([
    'primary' => Color::hex('#4F46E5'), // Indigo – thay thế Amber mặc định
])
```

---

## 🚀 Hướng dẫn cài đặt

### Yêu cầu hệ thống
- PHP >= 8.2
- Composer
- MySQL 8.0
- Node.js (tùy chọn)

### Các bước cài đặt

```bash
# 1. Clone repo
git clone https://github.com/TÊN_BẠN/laravel-filament-23810310102.git
cd laravel-filament-23810310102

# 2. Cài dependencies
composer install

# 3. Tạo file .env
copy .env.example .env
php artisan key:generate

# 4. Cấu hình database trong .env
DB_DATABASE=filament_admin_23810310102
DB_USERNAME=root
DB_PASSWORD=

# 5. Chạy migration & seed dữ liệu mẫu
php artisan migrate
php artisan db:seed

# 6. Tạo tài khoản admin
php artisan make:filament-user

# 7. Tạo storage link
php artisan storage:link

# 8. Chạy server
php artisan serve
```

Truy cập: **http://localhost:8000/admin**

---

## 📁 Cấu trúc thư mục chính

```
app/
├── Filament/
│   └── Resources/
│       ├── CategoryResource.php
│       ├── CategoryResource/Pages/
│       │   ├── ListCategories.php
│       │   ├── CreateCategory.php
│       │   └── EditCategory.php
│       ├── ProductResource.php
│       └── ProductResource/Pages/
│           ├── ListProducts.php
│           ├── CreateProduct.php
│           └── EditProduct.php
├── Models/
│   ├── Category.php
│   └── Product.php
└── Providers/Filament/
    └── AdminPanelProvider.php
database/
├── migrations/
│   ├── ..._create_23810310102_categories_table.php
│   └── ..._create_23810310102_products_table.php
└── seeders/
    └── DatabaseSeeder.php
```

---

## 📝 Lịch sử Commits

| # | Commit | Nội dung |
|---|--------|----------|
| 1 | `feat` | Khởi tạo dự án Laravel 12 và cài đặt Filament v3.3 |
| 2 | `feat` | Tạo migration và model Category/Product với prefix MSSV |
| 3 | `feat` | Xây dựng CategoryResource với auto-slug và bộ lọc is_visible |
| 4 | `feat` | Xây dựng ProductResource với grid layout, rich editor, upload ảnh |
| 5 | `feat` | Thêm discount_percent, đổi primary color Indigo, thêm seeder |

---

## 📸 Giao diện

### Dashboard
> Màu Indigo `#4F46E5` làm màu chủ đạo thay thế Amber mặc định

### Quản lý Danh mục
> Slug: `/admin/23810310102-categories` | Bộ lọc is_visible | Đếm số sản phẩm

### Quản lý Sản phẩm  
> Slug: `/admin/23810310102-products` | Grid 3 cột | Giá VNĐ | Badge giảm giá

---

<p align="center">
  Made with ❤️ by <strong>Nguyễn Công Sơn</strong> – MSSV 23810310102
</p>
