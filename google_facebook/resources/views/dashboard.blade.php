<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông tin người dùng</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eef2f7;
            margin: 0;
            padding: 40px;
        }
        .container {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0,0,0,.08);
        }
        img {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
        }
        .info {
            line-height: 1.8;
        }
        button {
            background: #dc2626;
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 20px;
        }
        .student-box {
            margin-top: 20px;
            background: #f8fafc;
            padding: 12px;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Đăng nhập thành công</h2>

        @if(auth()->user()->avatar)
            <img src="{{ auth()->user()->avatar }}" alt="Avatar">
        @endif

        <div class="info">
            <strong>Tên người dùng:</strong> {{ auth()->user()->name }}<br>
            <strong>Email:</strong> {{ auth()->user()->email }}<br>
            <strong>Student ID:</strong> {{ auth()->user()->student_id }}<br>
            <strong>Provider:</strong> {{ auth()->user()->provider }}<br>
        </div>

        <div class="student-box">
            <strong>Họ tên sinh viên:</strong> Nguyễn Công Sơn<br>
            <strong>Mã sinh viên:</strong> 23810310102
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">Đăng xuất</button>
        </form>
    </div>
</body>
</html>