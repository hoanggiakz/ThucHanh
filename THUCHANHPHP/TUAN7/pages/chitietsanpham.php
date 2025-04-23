<?php
require_once '../layout/header.php';
require_once '../class/csdltmdt.php';

if (!isset($_GET['idsp']) || !filter_var($_GET['idsp'], FILTER_VALIDATE_INT)) {
    echo "<p style='text-align: center; color: red;'>Sản phẩm không tồn tại!</p>";
    require_once '../layout/footer.php';
    exit();
}

$idsp = $_GET['idsp'];
$db = new csdltmdt();
$link = $db->connect();
$sql = "SELECT sp.*, ct.tencty 
        FROM SANPHAM sp 
        JOIN CONGTY ct ON sp.idcty = ct.idcty 
        WHERE sp.idsp = ?";
$stmt = $link->prepare($sql);
$stmt->execute([$idsp]);
$sp = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$sp) {
    echo "<p style='text-align: center; color: red;'>Sản phẩm không tồn tại!</p>";
    require_once '../layout/footer.php';
    exit();
}

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['idkh'])) {
        $msg = "Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng! <a href='/THUCHANHPHP/TUAN7/pages/dangnhap.php'>Đăng nhập ngay</a>";
    } else {
        $soluong = isset($_POST['soluong']) ? (int)$_POST['soluong'] : 1;
        if ($soluong <= 0) {
            $msg = "Số lượng phải lớn hơn 0!";
        } else {
            if (!isset($_SESSION['giohang'])) {
                $_SESSION['giohang'] = [];
            }
            if (isset($_SESSION['giohang'][$idsp])) {
                $_SESSION['giohang'][$idsp] += $soluong;
            } else {
                $_SESSION['giohang'][$idsp] = $soluong;
            }
            $msg = "Thêm vào giỏ hàng thành công! <a href='/THUCHANHPHP/TUAN7/pages/giohang.php'>Xem giỏ hàng</a>";
        }
    }
}
?>

<h2>Chi Tiết Sản Phẩm</h2>
<div class="product-detail">
    <div class="product-image">
        <img src="<?php echo htmlspecialchars($sp['hinh']); ?>" alt="<?php echo htmlspecialchars($sp['tensp']); ?>">
    </div>
    <div class="product-info">
        <h3><?php echo htmlspecialchars($sp['tensp']); ?></h3>
        <p><strong>Thương hiệu:</strong> <?php echo htmlspecialchars($sp['tencty']); ?></p>
        <p><strong>Giá:</strong> <?php echo number_format($sp['gia'], 0, ',', '.') . ' VNĐ'; ?></p>
        <p><strong>Giảm giá:</strong>
            <?php echo $sp['giamgia'] ? number_format($sp['giamgia'], 0, ',', '.') . ' VNĐ' : 'Không có'; ?></p>
        <p><strong>Mô tả:</strong> <?php echo htmlspecialchars($sp['mota']); ?></p>
        <form method="POST" action="">
            <label for="soluong">Số lượng:</label>
            <input type="number" name="soluong" id="soluong" value="1" min="1" style="width: 60px;">
            <input type="submit" value="Thêm vào giỏ" class="btn">
        </form>
        <p style="color: green; margin-top: 10px;"><?php echo $msg; ?></p>
    </div>
</div>

<div style="text-align: center; margin-top: 20px;">
    <a href="/THUCHANHPHP/TUAN7/index.php" class="btn">Quay lại danh sách sản phẩm</a>
</div>

<?php
require_once '../layout/footer.php';
?>