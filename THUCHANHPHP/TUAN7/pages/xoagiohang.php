<?php
session_start();

if (!isset($_GET['idsp']) || !filter_var($_GET['idsp'], FILTER_VALIDATE_INT)) {
    header("Location: /THUCHANHPHP/TUAN7/pages/giohang.php");
    exit();
}

$idsp = $_GET['idsp'];
if (isset($_SESSION['giohang'][$idsp])) {
    unset($_SESSION['giohang'][$idsp]); // Xóa sản phẩm khỏi giỏ hàng
    if (empty($_SESSION['giohang'])) {
        unset($_SESSION['giohang']); // Xóa toàn bộ giỏ hàng nếu không còn sản phẩm
    }
}

header("Location: /THUCHANHPHP/TUAN7/pages/giohang.php");
exit();
?>