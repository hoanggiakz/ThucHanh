<?php
session_start();
require_once '../class/csdltmdt.php';

// Kiểm tra đăng nhập admin
if (!isset($_SESSION['admin'])) {
    header("Location: /THUCHANHPHP/TUAN7/pages/dangnhap.php");
    exit();
}

// Kiểm tra idsp từ query string
if (!isset($_GET['idsp']) || !filter_var($_GET['idsp'], FILTER_VALIDATE_INT)) {
    header("Location: /THUCHANHPHP/TUAN7/pages/quanlysanpham.php");
    exit();
}

$idsp = $_GET['idsp'];
$db = new csdltmdt();
$conn = $db->connect();

// Kiểm tra xem sản phẩm có trong đơn hàng không
if ($db->checkSanPhamInDonHang($idsp)) {
    // Nếu sản phẩm có trong đơn hàng, không cho phép xóa
    header("Location: /THUCHANHPHP/TUAN7/pages/quanlysanpham.php?error=Sản phẩm đã có trong đơn hàng, không thể xóa!");
    exit();
}

// Lấy thông tin sản phẩm để xóa hình ảnh (nếu có)
$sql = "SELECT hinh FROM SANPHAM WHERE idsp = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$idsp]);
$sanpham = $stmt->fetch(PDO::FETCH_ASSOC);

if ($sanpham && !empty($sanpham['hinh'])) {
    $imagePath = $_SERVER['DOCUMENT_ROOT'] . '/THUCHANHPHP/TUAN7/images/' . $sanpham['hinh'];
    if (file_exists($imagePath)) {
        unlink($imagePath); // Xóa hình ảnh
    }
}

// Xóa sản phẩm khỏi bảng SANPHAM
$sql = "DELETE FROM SANPHAM WHERE idsp = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$idsp]);

header("Location: /THUCHANHPHP/TUAN7/pages/quanlysanpham.php?success=Xóa sản phẩm thành công!");
exit();
?>