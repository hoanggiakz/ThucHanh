<?php
require_once '../layout/header.php';
require_once '../class/csdltmdt.php';

// Kiểm tra đăng nhập admin
if (!isset($_SESSION['admin'])) {
    echo "<p style='text-align: center; color: red;'>Bạn cần đăng nhập với tài khoản admin để truy cập trang này! <a href='/THUCHANHPHP/TUAN7/pages/dangnhap.php'>Đăng nhập ngay</a></p>";
    require_once '../layout/footer.php';
    exit();
}

$db = new csdltmdt();
$donhangs = $db->getAllDonHang();
?>

<h2>Quản Lý Đơn Hàng</h2>
<?php if (count($donhangs) > 0): ?>
    <table align="center" border="1">
        <thead>
            <tr>
                <th>Mã đơn hàng</th>
                <th>Khách hàng</th>
                <th>Ngày đặt hàng</th>
                <th>Số lượng</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($donhangs as $dh): ?>
                <tr>
                    <td><?php echo htmlspecialchars($dh['iddh']); ?></td>
                    <td><?php echo htmlspecialchars($dh['tenkh']); ?></td>
                    <td><?php echo htmlspecialchars($dh['ngaydh']); ?></td>
                    <td><?php echo htmlspecialchars($dh['soluong'] ?? 0); ?></td>
                    <td><?php echo htmlspecialchars($dh['trangthai']); ?></td>
                    <td>
                        <a href="/THUCHANHPHP/TUAN7/pages/capnhatdonhang.php?iddh=<?php echo $dh['iddh']; ?>" class="btn">Cập nhật</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p style="text-align: center;">Chưa có đơn hàng nào!</p>
<?php endif; ?>

<div style="text-align: center; margin-top: 20px;">
    <a href="/THUCHANHPHP/TUAN7/pages/themdonhang.php" class="btn">Thêm đơn hàng</a>
    <a href="/THUCHANHPHP/TUAN7/index.php" class="btn">Quay lại trang chủ</a>
</div>

<?php
require_once '../layout/footer.php';
?>