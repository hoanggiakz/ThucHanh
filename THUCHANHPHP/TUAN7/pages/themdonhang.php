<?php
require_once '../layout/header.php';
require_once '../class/csdltmdt.php';
require_once '../class/clskhachhang.php';

// Kiểm tra đăng nhập admin
if (!isset($_SESSION['admin'])) {
    echo "<p style='text-align: center; color: red;'>Bạn cần đăng nhập với tài khoản admin để truy cập trang này! <a href='/THUCHANHPHP/TUAN7/pages/dangnhap.php'>Đăng nhập ngay</a></p>";
    require_once '../layout/footer.php';
    exit();
}

$khachhang = new clskhachhang();
$khachhangs = $khachhang->getAllKhachHang();

$db = new csdltmdt();
$conn = $db->connect(); // Lưu kết nối vào biến

// Lấy danh sách sản phẩm
$sql = "SELECT idsp, tensp FROM SANPHAM";
$stmt = $conn->prepare($sql);
$stmt->execute();
$sanphams = $stmt->fetchAll(PDO::FETCH_ASSOC);

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idkh = $_POST['idkh'] ?? '';
    $ngaydh = $_POST['ngaydh'] ?? '';
    $trangthai = $_POST['trangthai'] ?? '';
    $idsp = $_POST['idsp'] ?? '';
    $soluong = $_POST['soluong'] ?? '';

    if (empty($idkh) || empty($ngaydh) || empty($trangthai) || empty($idsp) || empty($soluong)) {
        $msg = "Vui lòng điền đầy đủ thông tin!";
    } else {
        // Bắt đầu giao dịch
        $conn->beginTransaction();
        try {
            // Thêm đơn hàng vào bảng DONHANG
            $sql = "INSERT INTO DONHANG (idkh, ngaydh, trangthai) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$idkh, $ngaydh, $trangthai]);

            // Lấy iddh của đơn hàng vừa thêm
            $iddh = $conn->lastInsertId();

            // Thêm chi tiết đơn hàng vào bảng CHITIETDONHANG
            $sql = "INSERT INTO CHITIETDONHANG (iddh, idsp, soluong) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$iddh, $idsp, $soluong]);

            // Xác nhận giao dịch
            $conn->commit();
            $msg = "Thêm đơn hàng thành công!";
        } catch (Exception $e) {
            // Hủy giao dịch nếu có lỗi
            $conn->rollBack();
            $msg = "Lỗi: " . $e->getMessage();
        }
    }
}
?>

<h2>Thêm Đơn Hàng</h2>
<p style="text-align: center; color: green;"><?php echo $msg; ?></p>
<form method="POST" action="">
    <table align="center" border="1">
        <tr>
            <th>Khách hàng</th>
            <td>
                <select name="idkh" required>
                    <option value="">-- Chọn khách hàng --</option>
                    <?php foreach ($khachhangs as $kh): ?>
                        <option value="<?php echo htmlspecialchars($kh['idkh']); ?>">
                            <?php echo htmlspecialchars($kh['tenkh']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th>Ngày đặt hàng</th>
            <td><input type="date" name="ngaydh" value="<?php echo date('Y-m-d'); ?>" required></td>
        </tr>
        <tr>
            <th>Trạng thái</th>
            <td>
                <select name="trangthai" required>
                    <option value="Chờ xử lý">Chờ xử lý</option>
                    <option value="Đang giao">Đang giao</option>
                    <option value="Đã giao">Đã giao</option>
                    <option value="Hủy">Hủy</option>
                </select>
            </td>
        </tr>
        <tr>
            <th>Sản phẩm</th>
            <td>
                <select name="idsp" required>
                    <option value="">-- Chọn sản phẩm --</option>
                    <?php foreach ($sanphams as $sp): ?>
                        <option value="<?php echo htmlspecialchars($sp['idsp']); ?>">
                            <?php echo htmlspecialchars($sp['tensp']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th>Số lượng</th>
            <td><input type="number" name="soluong" min="1" value="1" required></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">
                <input type="submit" value="Thêm đơn hàng" class="btn">
                <a href="/THUCHANHPHP/TUAN7/pages/quanlydonhang.php" class="btn">Quay lại</a>
            </td>
        </tr>
    </table>
</form>

<?php
require_once '../layout/footer.php';
?>