<?php
require_once '../layout/header.php';
require_once '../class/clskhachhang.php';

// Kiểm tra đăng nhập khách hàng
if (!isset($_SESSION['idkh'])) {
    echo "<p style='text-align: center; color: red;'>Bạn cần đăng nhập để truy cập trang này! <a href='/THUCHANHPHP/TUAN7/pages/dangnhap.php'>Đăng nhập ngay</a></p>";
    require_once '../layout/footer.php';
    exit();
}

$idkh = $_SESSION['idkh'];
$khachhang = new clskhachhang();
$kh = $khachhang->getKhachHangById($idkh);

if (!$kh) {
    echo "<p style='text-align: center; color: red;'>Khách hàng không tồn tại!</p>";
    require_once '../layout/footer.php';
    exit();
}

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $diachi = $_POST['diachi'] ?? '';
    $dienthoai = $_POST['dienthoai'] ?? '';

    if (empty($diachi) || empty($dienthoai)) {
        $msg = "Vui lòng điền đầy đủ thông tin!";
    } else {
        $khachhang->capNhatKhachHang($idkh, $kh['hodem'], $kh['ten'], $diachi, $dienthoai, $kh['email']);
        $msg = "Cập nhật địa chỉ nhận hàng thành công!";
    }
}
?>

<h2>Cập Nhật Địa Chỉ Nhận Hàng</h2>
<p style="text-align: center; color: green;"><?php echo $msg; ?></p>
<form method="POST" action="">
    <table align="center" border="1">
        <tr>
            <th>Tên khách hàng</th>
            <td>
                <input type="text" value="<?php echo htmlspecialchars($kh['hodem'] . ' ' . $kh['ten']); ?>" disabled>
            </td>
        </tr>
        <tr>
            <th>Địa chỉ</th>
            <td>
                <input type="text" name="diachi" value="<?php echo htmlspecialchars($kh['diachi']); ?>" required>
            </td>
        </tr>
        <tr>
            <th>Số điện thoại</th>
            <td>
                <input type="text" name="dienthoai" value="<?php echo htmlspecialchars($kh['dienthoai']); ?>" required>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">
                <input type="submit" value="Cập nhật" class="btn">
                <a href="/THUCHANHPHP/TUAN7/index.php" class="btn">Quay lại</a>
            </td>
        </tr>
    </table>
</form>

<?php
require_once '../layout/footer.php';
?>