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

// Kiểm tra iddh từ query string
if (!isset($_GET['iddh']) || !filter_var($_GET['iddh'], FILTER_VALIDATE_INT)) {
    echo "<p style='text-align: center; color: red;'>Mã đơn hàng không hợp lệ!</p>";
    require_once '../layout/footer.php';
    exit();
}

$iddh = $_GET['iddh'];
$db = new csdltmdt();
$conn = $db->connect(); // Lưu kết nối vào biến

// Lấy thông tin đơn hàng
$sql = "SELECT dh.iddh, dh.idkh, dh.ngaydh, dh.trangthai, kh.hodem, kh.ten 
        FROM DONHANG dh 
        JOIN KHACHHANG kh ON dh.idkh = kh.idkh 
        WHERE dh.iddh = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$iddh]);
$donhang = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$donhang) {
    echo "<p style='text-align: center; color: red;'>Đơn hàng không tồn tại!</p>";
    require_once '../layout/footer.php';
    exit();
}

// Lấy chi tiết đơn hàng
$sql = "SELECT ctdh.idsp, ctdh.soluong, sp.tensp 
        FROM CHITIETDONHANG ctdh 
        JOIN SANPHAM sp ON ctdh.idsp = sp.idsp 
        WHERE ctdh.iddh = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$iddh]);
$chitiet = $stmt->fetch(PDO::FETCH_ASSOC);

// Lấy danh sách khách hàng
$khachhang = new clskhachhang();
$khachhangs = $khachhang->getAllKhachHang();

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
            // Cập nhật bảng DONHANG
            $sql = "UPDATE DONHANG SET idkh = ?, ngaydh = ?, trangthai = ? WHERE iddh = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$idkh, $ngaydh, $trangthai, $iddh]);

            // Cập nhật bảng CHITIETDONHANG
            $sql = "UPDATE CHITIETDONHANG SET idsp = ?, soluong = ? WHERE iddh = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$idsp, $soluong, $iddh]);

            // Xác nhận giao dịch
            $conn->commit();
            $msg = "Cập nhật đơn hàng thành công!";
        } catch (Exception $e) {
            // Hủy giao dịch nếu có lỗi
            $conn->rollBack();
            $msg = "Lỗi: " . $e->getMessage();
        }
    }
}
?>

<h2>Cập Nhật Đơn Hàng</h2>
<p style="text-align: center; color: green;"><?php echo $msg; ?></p>
<form method="POST" action="">
    <table align="center" border="1">
        <tr>
            <th>Mã đơn hàng</th>
            <td>
                <input type="text" value="<?php echo htmlspecialchars($donhang['iddh']); ?>" disabled>
            </td>
        </tr>
        <tr>
            <th>Khách hàng</th>
            <td>
                <select name="idkh" required>
                    <option value="">-- Chọn khách hàng --</option>
                    <?php foreach ($khachhangs as $kh): ?>
                        <option value="<?php echo htmlspecialchars($kh['idkh']); ?>" <?php echo $kh['idkh'] == $donhang['idkh'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($kh['tenkh']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th>Ngày đặt hàng</th>
            <td><input type="date" name="ngaydh" value="<?php echo htmlspecialchars($donhang['ngaydh']); ?>" required></td>
        </tr>
        <tr>
            <th>Trạng thái</th>
            <td>
                <select name="trangthai" required>
                    <option value="Chờ xử lý" <?php echo $donhang['trangthai'] == 'Chờ xử lý' ? 'selected' : ''; ?>>Chờ xử lý</option>
                    <option value="Đang giao" <?php echo $donhang['trangthai'] == 'Đang giao' ? 'selected' : ''; ?>>Đang giao</option>
                    <option value="Đã giao" <?php echo $donhang['trangthai'] == 'Đã giao' ? 'selected' : ''; ?>>Đã giao</option>
                    <option value="Hủy" <?php echo $donhang['trangthai'] == 'Hủy' ? 'selected' : ''; ?>>Hủy</option>
                </select>
            </td>
        </tr>
        <tr>
            <th>Sản phẩm</th>
            <td>
                <select name="idsp" required>
                    <option value="">-- Chọn sản phẩm --</option>
                    <?php foreach ($sanphams as $sp): ?>
                        <option value="<?php echo htmlspecialchars($sp['idsp']); ?>" <?php echo isset($chitiet['idsp']) && $sp['idsp'] == $chitiet['idsp'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($sp['tensp']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th>Số lượng</th>
            <td>
                <input type="number" name="soluong" min="1" value="<?php echo isset($chitiet['soluong']) ? htmlspecialchars($chitiet['soluong']) : '1'; ?>" required>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">
                <input type="submit" value="Cập nhật đơn hàng" class="btn">
                <a href="/THUCHANHPHP/TUAN7/pages/quanlydonhang.php" class="btn">Quay lại</a>
            </td>
        </tr>
    </table>
</form>

<?php
require_once '../layout/footer.php';
?>