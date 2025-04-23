<?php
require_once '../layout/header.php';
require_once '../class/clskhachhang.php';

// Kiểm tra đăng nhập admin
if (!isset($_SESSION['admin'])) {
    echo "<p style='text-align: center; color: red;'>Bạn cần đăng nhập với tài khoản admin để truy cập trang này! <a href='/THUCHANHPHP/TUAN7/pages/dangnhap.php'>Đăng nhập ngay</a></p>";
    require_once '../layout/footer.php';
    exit();
}

$khachhang = new clskhachhang();
$khachhangs = $khachhang->getAllKhachHang();
?>

<h2>Quản Lý Khách Hàng</h2>
<?php if (count($khachhangs) > 0): ?>
    <table align="center" border="1">
        <thead>
            <tr>
                <th>Mã KH</th>
                <th>Tên khách hàng</th>
                <th>Địa chỉ</th>
                <th>Số điện thoại</th>
                <th>Email</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($khachhangs as $kh): ?>
                <tr>
                    <td><?php echo htmlspecialchars($kh['idkh']); ?></td>
                    <td><?php echo htmlspecialchars($kh['tenkh']); ?></td>
                    <td><?php echo htmlspecialchars($kh['diachi']); ?></td>
                    <td><?php echo htmlspecialchars($kh['dienthoai']); ?></td>
                    <td><?php echo htmlspecialchars($kh['email']); ?></td>
                    <td>
                        <a href="/THUCHANHPHP/TUAN7/pages/capnhatkhachhang.php?idkh=<?php echo $kh['idkh']; ?>" class="btn">Sửa</a>
                        <a href="/THUCHANHPHP/TUAN7/pages/xoakhachhang.php?idkh=<?php echo $kh['idkh']; ?>" class="btn" onclick="return confirm('Bạn có chắc chắn muốn xóa khách hàng này?');">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p style="text-align: center;">Chưa có khách hàng nào!</p>
<?php endif; ?>

<div style="text-align: center; margin-top: 20px;">
    <a href="/THUCHANHPHP/TUAN7/index.php" class="btn">Quay lại trang chủ</a>
</div>

<?php
require_once '../layout/footer.php';
?>