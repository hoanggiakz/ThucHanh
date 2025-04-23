<?php
require_once '../layout/header.php';
require_once '../class/clskhachhang.php';

// Kiểm tra đăng nhập admin
if (!isset($_SESSION['admin'])) {
    echo "<p style='text-align: center; color: red;'>Bạn cần đăng nhập với tài khoản admin để truy cập trang này! <a href='/THUCHANHPHP/TUAN7/pages/dangnhap.php'>Đăng nhập ngay</a></p>";
    require_once '../layout/footer.php';
    exit();
}

if (!isset($_GET['idkh']) || !filter_var($_GET['idkh'], FILTER_VALIDATE_INT)) {
    echo "<p style='text-align: center; color: red;'>Khách hàng không tồn tại!</p>";
    require_once '../layout/footer.php';
    exit();
}

$idkh = $_GET['idkh'];
$khachhang = new clskhachhang();
$kh = $khachhang->getKhachHangById($idkh);

if (!$kh) {
    echo "<p style='text-align: center; color: red;'>Khách hàng không tồn tại!</p>";
    require_once '../layout/footer.php';
    exit();
}

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hodem = $_POST['hodem'] ?? '';
    $ten = $_POST['ten'] ?? '';
    $diachi = $_POST['diachi'] ?? '';
    $dienthoai = $_POST['dienthoai'] ?? '';
    $email = $_POST['email'] ?? '';

    if (empty($hodem) || empty($ten) || empty($diachi) || empty($dienthoai) || empty($email)) {
        $msg = "Vui lòng điền đầy đủ thông tin!";
    } else {
        $khachhang->capNhatKhachHang($idkh, $hodem, $ten, $diachi, $dienthoai, $email);
        $msg = "Cập nhật khách hàng thành công!";
    }
}
?>

<h2>Cập Nhật Khách Hàng</h2>
<p style="text-align: center; color: green;"><?php echo $msg; ?></p>
<form method="POST" action="">
    <table align="center" border="1">
        <tr>
            <th>Họ và đệm</th>
            <td><input type="text" name="hodem" value="<?php echo htmlspecialchars($kh['hodem']); ?>"></td>
        </tr>
        <tr>
            <th>Tên</th>
            <td><input type="text" name="ten" value="<?php echo htmlspecialchars($kh['ten']); ?>"></td>
        </tr>
        <tr>
            <th>Địa chỉ</th>
            <td><input type="text" name="diachi" value="<?php echo htmlspecialchars($kh['diachi']); ?>"></td>
        </tr>
        <tr>
            <th>Số điện thoại</th>
            <td><input type="text" name="dienthoai" value="<?php echo htmlspecialchars($kh['dienthoai']); ?>"></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><input type="email" name="email" value="<?php echo htmlspecialchars($kh['email']); ?>"></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">
                <input type="submit" value="Cập nhật" class="btn">
                <a href="/THUCHANHPHP/TUAN7/pages/quanlykhachhang.php" class="btn">Quay lại</a>
            </td>
        </tr>
    </table>
</form>

<?php
require_once '../layout/footer.php';
?>