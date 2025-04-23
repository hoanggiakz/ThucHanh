<?php
session_start();
require_once '../class/csdltmdt.php';

if (!isset($_GET['idsp']) || !filter_var($_GET['idsp'], FILTER_VALIDATE_INT)) {
    header("Location: /THUCHANHPHP/TUAN7/index.php");
    exit();
}

$idsp = $_GET['idsp'];
$db = new csdltmdt();
$sql = "SELECT idsp, tensp, gia, hinh FROM SANPHAM WHERE idsp = ?";
$stmt = $db->connect()->prepare($sql);
$stmt->execute([$idsp]);
$sp = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$sp) {
    header("Location: /THUCHANHPHP/TUAN7/index.php");
    exit();
}

// Thêm sản phẩm vào giỏ hàng
if (!isset($_SESSION['giohang'])) {
    $_SESSION['giohang'] = [];
}

if (isset($_SESSION['giohang'][$idsp])) {
    $_SESSION['giohang'][$idsp]++; // Tăng số lượng nếu sản phẩm đã có trong giỏ
} else {
    $_SESSION['giohang'][$idsp] = 1; // Thêm sản phẩm mới với số lượng 1
}

header("Location: /THUCHANHPHP/TUAN7/pages/giohang.php");
exit();
?>