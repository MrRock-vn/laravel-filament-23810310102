<!--
Họ tên: Nguyễn Công Sơn
Mã sinh viên: 23810310102
Lớp: D18CNPM2
-->

# Laravel Social Login Integration

## Thông tin sinh viên

- Họ tên: Nguyễn Công Sơn
- Mã sinh viên: 23810310102
- Lớp: D18CNPM2

## Mô tả chung

Dự án xây dựng chức năng đăng nhập bằng tài khoản bên thứ ba trong ứng dụng web Laravel, sử dụng Laravel Socialite.

Hệ thống hỗ trợ:

- Đăng nhập bằng Google (OAuth 2.0)
- Đăng nhập bằng Facebook (OAuth 2.0)
- Lưu thông tin người dùng vào database
- Nếu tài khoản đã tồn tại thì đăng nhập
- Nếu chưa tồn tại thì tạo mới và đăng nhập
- Hiển thị thông tin người dùng sau khi đăng nhập
- Đăng xuất
- Xử lý lỗi khi đăng nhập thất bại hoặc người dùng từ chối cấp quyền

## Công nghệ sử dụng

- Laravel
- Laravel Socialite
- MySQL
- PHP 8.x

## Chức năng chính

### 1. Đăng nhập Google
Người dùng có thể đăng nhập bằng tài khoản Google thông qua OAuth 2.0.

### 2. Đăng nhập Facebook
Người dùng có thể đăng nhập bằng tài khoản Facebook thông qua OAuth 2.0.

### 3. Lưu dữ liệu người dùng
Sau khi đăng nhập thành công, hệ thống lưu các thông tin cơ bản vào bảng `users`:

- `name`
- `email`
- `avatar`
- `student_id`
- `provider`
- `provider_id`
- `google_id`
- `facebook_id`

### 4. Kiểm tra tài khoản tồn tại
- Nếu email đã có trong hệ thống → đăng nhập luôn
- Nếu chưa có → tạo tài khoản mới rồi đăng nhập

### 5. Hiển thị thông tin sau đăng nhập
Sau khi đăng nhập, giao diện hiển thị:

- Tên người dùng
- Email
- Avatar
- Student ID
- Provider
- Họ tên sinh viên
- Mã sinh viên

### 6. Đăng xuất
Người dùng có thể đăng xuất khỏi hệ thống.

## Cấu trúc thư mục chính

```text
app/
├── Http/
│   └── Controllers/
│       └── Auth/
│           └── SocialAuthController.php
├── Models/
│   └── User.php
├── Services/
│   └── SocialAuthService.php

config/
└── services.php

database/
└── migrations/
    └── xxxx_xx_xx_xxxxxx_add_social_fields_to_users_table.php

resources/
└── views/
    ├── auth/
    │   └── login.blade.php
    └── dashboard.blade.php

routes/
└── web.php
----
Hướng dẫn cài đặt
1. Clone project
git clone <link-github-cua-ban>
cd filament-admin

2. Cài thư viện
composer install

3. Tạo file môi trường
copy .env.example .env
php artisan key:generate

4. Cấu hình database trong .env

Ví dụ:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=filament_admin_23810310102
DB_USERNAME=root
DB_PASSWORD=
5. Chạy migration
php artisan migrate
6. Chạy project
php artisan serve

Truy cập:

http://localhost:8000/login
Cấu hình Google OAuth

Bước 1: Tạo OAuth Client trên Google Cloud
Truy cập Google Cloud Console
Tạo project
Vào Google Auth Platform / Credentials
Tạo OAuth Client ID
Chọn loại: Web application

Bước 2: Khai báo Redirect URI
http://127.0.0.1:8000/auth/google/callback

Bước 3: Điền vào .env
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/auth/google/callback

Cấu hình Facebook OAuth
Bước 1: Tạo App trên Meta for Developers
Truy cập Meta for Developers
Tạo app mới
Chọn use case: Đăng nhập bằng Facebook

Bước 2: Lấy App ID và App Secret

Điền vào .env:

FACEBOOK_CLIENT_ID=your_facebook_app_id
FACEBOOK_CLIENT_SECRET=your_facebook_app_secret
FACEBOOK_REDIRECT_URI=http://localhost:8000/auth/facebook/callback

Bước 3: Cấu hình Facebook Login

Trong phần Facebook Login / Settings, cấu hình theo môi trường phát triển localhost.

Cấu hình config/services.php

File config/services.php cần có:

'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URI'),
],

'facebook' => [
    'client_id' => env('FACEBOOK_CLIENT_ID'),
    'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
    'redirect' => env('FACEBOOK_REDIRECT_URI'),
],