<?php
require_once '../class/clskhachhang.php';

// Kiểm tra đăng nhập admin
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: /THUCHANHPHP/TUAN7/pages/dangnhap.php");
    exit();
}

if (!isset($_GET['idkh']) || !filter_var($_GET['idkh'], FILTER_VALIDATE_INT)) {
    header("Location: /THUCHANHPHP/TUAN7/pages/quanlykhachhang.php");
    exit();
}

$idkh = $_GET['idkh'];
$khachhang = new clskhachhang();
if ($khachhang->xoaKhachHang($idkh)) {
    header("Location: /THUCHANHPHP/TUAN7/pages/quanlykhachhang.php");
    exit();
} else {
    echo "<p style='text-align: center; color: red;'>Không thể xóa khách hàng này vì họ đã có đơn hàng!</p>";
    echo "<p style='text-align: center;'><a href='/THUCHANHPHP/TUAN7/pages/quanlykhachhang.php'>Quay lại</a></p>";
    exit();
}
?>