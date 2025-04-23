<?php
ob_start(); 
session_start();
require_once dirname(__DIR__) . '/class/csdltmdt.php';
$db = new csdltmdt();

// Lấy danh sách công ty (thương hiệu) cho menu
$congTys = $db->danhsachcongty_combobox();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Thương Mại Điện Tử - Tuần 6 & 7</title>
    <link rel="stylesheet" href="/THUCHANHPHP/TUAN7/css/style.css">
</head>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f4f4;
    }

    .auth-bar {
        text-align: right;
        padding: 10px;
        background-color: #333;
        color: white;
    }

    .auth-bar a,
    .auth-bar span {
        color: white;
        margin: 0 10px;
        text-decoration: none;
    }

    .btn {
        display: inline-block;
        padding: 5px 10px;
        background-color: #6c757d;
        color: white;
        border-radius: 5px;
        text-decoration: none;
        text-align: center;
    }

    .btn:hover {
        background-color: #5a6268;
    }

    .banner {
        text-align: center;
        padding: 20px;
        background-color: #d3d3d3;
    }

    .banner h1 {
        color: #333;
        font-size: 24px;
    }

    /* Canh giữa nội dung trang */
    .wrapper {
        display: flex;
        justify-content: center;
        padding: 20px 0;
    }

    .container {
        width: 1000px; /* Hoặc có thể dùng 90% */
        display: flex;
    }

    .sidebar {
        width: 200px;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 10px;
    }

    .sidebar h3 {
        margin-top: 0;
        color: #333;
    }

    .sidebar ul {
        list-style: none;
        padding: 0;
    }

    .sidebar ul li {
        margin: 10px 0;
    }

    .sidebar ul li a {
        color: #007bff;
        text-decoration: none;
    }

    .sidebar ul li a:hover {
        text-decoration: underline;
    }

    .main-content {
        flex: 1;
        padding: 20px;
        margin-left: 20px;
        background-color: #fff;
        border-radius: 5px;
    }

    h2, h3 {
        text-align: center;
        color: #333;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 250px));
        gap: 8px;
        padding: 30px;
    }

    .product-item {
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 6px;
        text-align: center;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .product-item img {
        width: 80px;
        height: auto;
        margin-bottom: 10px;
        padding-top: 10px;
    }

    .product-item h3 {
        font-size: 11px;
        margin: 2px 0;
        color: #333;
        height: 32px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .product-item .price {
        color: #dc3545;
        font-weight: bold;
        font-size: 11px;
        margin-bottom: 6px;
    }

    .product-item .btn {
        font-size: 9px;
        padding: 3px 6px;
    }

    footer {
        background-color: #808080;
        color: #fff;
        text-align: center;
        padding: 20px;
        width: 100%;
        margin-top: auto;
    }
</style>

<body>
    <!-- Thanh đăng nhập/đăng xuất -->
    <div class="auth-bar">
        <?php if (isset($_SESSION['admin'])): ?>
            <span>Xin chào, Admin!</span>
            <a href="/THUCHANHPHP/TUAN7/pages/dangxuat.php" class="btn">Đăng xuất</a>
        <?php elseif (isset($_SESSION['idkh'])): ?>
            <span>Xin chào, <?php echo htmlspecialchars($_SESSION['tenkh']); ?>!</span>
            <a href="/THUCHANHPHP/TUAN7/pages/dangxuat.php" class="btn">Đăng xuất</a>
        <?php else: ?>
            <a href="/THUCHANHPHP/TUAN7/pages/dangnhap.php" class="btn">Đăng nhập</a>
            <a href="/THUCHANHPHP/TUAN7/pages/dangky.php" class="btn">Đăng ký</a>
        <?php endif; ?>
    </div>

    <!-- Banner -->
    <div class="banner">
        <h1>Baner </h1>
    </div>

    <!-- Canh giữa trang -->
    <div class="wrapper">
        <div class="container">
            <!-- Menu bên trái -->
            <div class="sidebar">
                <h3>MENU</h3>
                <ul>
                    <li><a href="/THUCHANHPHP/TUAN7/index.php">Tất cả</a></li>
                    <?php foreach ($congTys as $cty): ?>
                        <li><a href="/THUCHANHPHP/TUAN7/index.php?thuonghieu=<?php echo $cty['idcty']; ?>"><?php echo htmlspecialchars($cty['tencty']); ?></a></li>
                    <?php endforeach; ?>
                    <?php if (isset($_SESSION['idkh'])): ?>
                        <li><a href="/THUCHANHPHP/TUAN7/pages/giohang.php">Giỏ hàng</a></li>
                        <li><a href="/THUCHANHPHP/TUAN7/pages/capnhatdiachinhanhang.php">Cập nhật Địa chỉ</a></li>
                        <li><a href="/THUCHANHPHP/TUAN7/pages/chitietdonhang.php">Xác nhận Đặt hàng</a></li>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['admin'])): ?>
                        <li><a href="/THUCHANHPHP/TUAN7/pages/quanlysanpham.php">Quản lý Sản phẩm</a></li>
                        <li><a href="/THUCHANHPHP/TUAN7/pages/quanlydonhang.php">Quản lý Đơn hàng</a></li>
                        <li><a href="/THUCHANHPHP/TUAN7/pages/quanlykhachhang.php">Quản lý Khách hàng</a></li>
                        <li><a href="/THUCHANHPHP/TUAN7/pages/thongkemua.php">Thống kê Mua hàng</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Nội dung chính -->
            <div class="main-content">
                <!-- Nội dung trang chính sẽ đi vào đây -->
            
