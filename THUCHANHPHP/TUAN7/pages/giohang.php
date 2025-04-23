<?php
require_once '../layout/header.php';
require_once '../class/csdltmdt.php';

$db = new csdltmdt();
$giohang = isset($_SESSION['giohang']) ? $_SESSION['giohang'] : [];
$cart_items = [];
$tongtien = 0;

// Lấy thông tin sản phẩm từ bảng SANPHAM dựa trên idsp trong giỏ hàng
if (!empty($giohang)) {
    $ids = array_keys($giohang); // Lấy danh sách idsp từ giỏ hàng
    if (!empty($ids)) {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "SELECT idsp, tensp, gia, hinh FROM SANPHAM WHERE idsp IN ($placeholders)";
        $stmt = $db->connect()->prepare($sql);
        $stmt->execute($ids);
        $sanphams = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Kết hợp thông tin sản phẩm với số lượng từ giỏ hàng
        foreach ($sanphams as $sp) {
            $idsp = $sp['idsp'];
            $cart_items[$idsp] = [
                'idsp' => $sp['idsp'],
                'tensp' => $sp['tensp'],
                'gia' => $sp['gia'],
                'hinh' => $sp['hinh'],
                'soluong' => $giohang[$idsp]
            ];
            $tongtien += $sp['gia'] * $giohang[$idsp];
        }
    }
}
?>

<h2>Giỏ Hàng</h2>
<?php if (!empty($cart_items)): ?>
    <table align="center" border="1">
        <thead>
            <tr>
                <th>Hình ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Tổng tiền</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cart_items as $item): ?>
                <tr>
                    <td>
                        <?php if (!empty($item['hinh'])): ?>
                            <img src="<?php echo htmlspecialchars($item['hinh']); ?>" alt="<?php echo htmlspecialchars($item['tensp']); ?>" width="80">
                        <?php else: ?>
                            <span>Không có hình ảnh</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($item['tensp']); ?></td>
                    <td><?php echo number_format($item['gia'], 0, ',', '.') . ' VNĐ'; ?></td>
                    <td><?php echo htmlspecialchars($item['soluong']); ?></td>
                    <td><?php echo number_format($item['gia'] * $item['soluong'], 0, ',', '.') . ' VNĐ'; ?></td>
                    <td>
                        <a href="/THUCHANHPHP/TUAN7/pages/xoagiohang.php?idsp=<?php echo $item['idsp']; ?>" class="btn" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?');">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p style="text-align: center; font-weight: bold;">Tổng tiền: <?php echo number_format($tongtien, 0, ',', '.') . ' VNĐ'; ?></p>
    <div style="text-align: center; margin-top: 20px;">
        <a href="/THUCHANHPHP/TUAN7/pages/dathang.php" class="btn">Đặt hàng</a>
        <a href="/THUCHANHPHP/TUAN7/index.php" class="btn">Tiếp tục mua sắm</a>
    </div>
<?php else: ?>
    <p style="text-align: center;">Giỏ hàng của bạn đang trống!</p>
    <div style="text-align: center; margin-top: 20px;">
        <a href="/THUCHANHPHP/TUAN7/index.php" class="btn">Quay lại trang chủ</a>
    </div>
<?php endif; ?>

<?php
require_once '../layout/footer.php';
?>