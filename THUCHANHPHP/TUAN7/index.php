<?php
require_once 'layout/header.php';
require_once 'class/csdltmdt.php';

$db = new csdltmdt();

// Lọc theo thương hiệu nếu có
$where = '';
$params = array();

if (isset($_GET['thuonghieu']) && !empty($_GET['thuonghieu'])) {
    $thuonghieu = filter_var($_GET['thuonghieu'], FILTER_VALIDATE_INT);
    if ($thuonghieu > 0) {
        $where = " WHERE sp.idcty = ?";
        $params[] = $thuonghieu;
    }
}

$sql = "SELECT sp.idsp, sp.tensp, sp.gia, sp.hinh, ct.tencty 
        FROM SANPHAM sp 
        JOIN CONGTY ct ON sp.idcty = ct.idcty" . $where;

$stmt = $db->connect()->prepare($sql);
$stmt->execute($params);
$sanphams = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php if (count($sanphams) > 0): ?>
    <div class="product-grid">
        <?php foreach ($sanphams as $sp): ?>
            <div class="product-item">
                <img src="<?php echo htmlspecialchars($sp['hinh']); ?>" alt="<?php echo htmlspecialchars($sp['tensp']); ?>">
                <h3><?php echo htmlspecialchars($sp['tensp']); ?></h3>
                <p class="price"><?php echo number_format($sp['gia'], 0, ',', '.') . ' VNĐ'; ?></p>
                <a href="/THUCHANHPHP/TUAN7/pages/chitietsanpham.php?idsp=<?php echo $sp['idsp']; ?>" class="btn">Thêm vào giỏ</a>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p style="text-align: center;">Chưa có sản phẩm nào!</p>
<?php endif; ?>

<?php require_once 'layout/footer.php'; ?>
