<?php
require_once '../layout/header.php';
require_once '../class/csdltmdt.php';

// Khởi tạo session
//session_start();

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['idkh'])) {
    echo "<p style='text-align: center; color: red;'>Bạn cần đăng nhập để đặt hàng! <a href='/THUCHANHPHP/TUAN6/pages/dangnhap.php'>Đăng nhập ngay</a></p>";
    require_once '../layout/footer.php';
    exit();
}

// Lấy idkh từ session
$idkh = $_SESSION['idkh'];

// Lấy idsp từ GET
$idsp = isset($_GET['idsp']) ? $_GET['idsp'] : null;

// Khởi tạo đối tượng csdltmdt
$db = new csdltmdt();

// Kiểm tra idsp có hợp lệ không
if (!$idsp) {
    echo "<p style='text-align: center; color: red;'>Không tìm thấy sản phẩm để đặt hàng! <a href='/THUCHANHPHP/TUAN6/index.php'>Quay lại trang chủ</a></p>";
    require_once '../layout/footer.php';
    exit();
}

// Kiểm tra sản phẩm có tồn tại không
$sanpham = $db->getSanPhamById($idsp);
if (!$sanpham) {
    echo "<p style='text-align: center; color: red;'>Sản phẩm không tồn tại! <a href='/THUCHANHPHP/TUAN6/index.php'>Quay lại trang chủ</a></p>";
    require_once '../layout/footer.php';
    exit();
}

// Lưu đơn hàng vào cơ sở dữ liệu
try {
    $link = $db->connect();
    $sql = "INSERT INTO DONHANG (idkh, idsp, ngaydh, trangthai) VALUES (?, ?, ?, ?)";
    $stmt = $link->prepare($sql);
    $ngaydh = date('Y-m-d'); // Lấy ngày hiện tại
    $trangthai = 'Chờ xử lý';
    $stmt->execute([$idkh, $idsp, $ngaydh, $trangthai]);

    // Hiển thị thông báo xác nhận
    echo "<div class='confirmation-message'>";
    echo "<h2>Đặt hàng thành công!</h2>";
    echo "<p>Cảm ơn bạn đã đặt hàng. Đơn hàng của bạn đang được xử lý.</p>";
    echo "<p><strong>Sản phẩm:</strong> " . htmlspecialchars($sanpham['tensp']) . "</p>";
    echo "<p><strong>Ngày đặt hàng:</strong> " . $ngaydh . "</p>";
    echo "<p><strong>Trạng thái:</strong> " . $trangthai . "</p>";
    echo "<a href='/THUCHANHPHP/TUAN6/index.php' class='btn'>Quay lại trang chủ</a>";
    echo "</div>";
} catch (PDOException $e) {
    echo "<p style='text-align: center; color: red;'>Lỗi khi đặt hàng: " . $e->getMessage() . "</p>";
}

require_once '../layout/footer.php';
?>