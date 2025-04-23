<?php
require_once '../layout/header.php';
require_once '../class/csdltmdt.php';

// Kiểm tra đăng nhập khách hàng
if (!isset($_SESSION['idkh'])) {
    echo "<p style='text-align: center; color: red;'>Bạn cần đăng nhập để xem chi tiết đơn hàng! <a href='/THUCHANHPHP/TUAN7/pages/dangnhap.php'>Đăng nhập ngay</a></p>";
    require_once '../layout/footer.php';
    exit();
}

$idkh = $_SESSION['idkh'];
$db = new csdltmdt();
$donhangs = $db->getDonHangByKhachHang($idkh);
?>

<h2>Chi Tiết Đơn Hàng</h2>
<?php if (!empty($donhangs)): ?>
    <table align="center" border="1">
        <thead>
            <tr>
                <th>Mã đơn hàng</th>
                <th>Ngày đặt hàng</th>
                <th>Trạng thái</th>
                <th>Sản phẩm</th>
                <th>Hình ảnh</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Tổng tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($donhangs as $dh): ?>
                <tr>
                    <td><?php echo htmlspecialchars($dh['iddh']); ?></td>
                    <td><?php echo htmlspecialchars($dh['ngaydh']); ?></td>
                    <td><?php echo htmlspecialchars($dh['trangthai']); ?></td>
                    <td><?php echo htmlspecialchars($dh['tensp']); ?></td>
                    <td>
                        <?php if (!empty($dh['hinh'])): ?>
                            <img src="<?php echo htmlspecialchars($dh['hinh']); ?>" alt="<?php echo htmlspecialchars($dh['tensp']); ?>" width="80">
                        <?php else: ?>
                            <span>Không có hình ảnh</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo number_format($dh['gia'], 0, ',', '.') . ' VNĐ'; ?></td>
                    <td><?php echo htmlspecialchars($dh['soluong']); ?></td>
                    <td><?php echo number_format($dh['gia'] * $dh['soluong'], 0, ',', '.') . ' VNĐ'; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p style="text-align: center;">Bạn chưa có đơn hàng nào!</p>
<?php endif; ?>

<div style="text-align: center; margin-top: 20px;">
    <a href="/THUCHANHPHP/TUAN7/index.php" class="btn">Quay lại trang chủ</a>
</div>

<?php
require_once '../layout/footer.php';
?>