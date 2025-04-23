<?php
require_once '../layout/header.php';
require_once '../class/clskhachhang.php';

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hodem = $_POST['hodem'] ?? '';
    $ten = $_POST['ten'] ?? '';
    $diachi = $_POST['diachi'] ?? '';
    $dienthoai = $_POST['dienthoai'] ?? '';
    $email = $_POST['email'] ?? '';
    $matkhau = $_POST['matkhau'] ?? '';
    $matkhau2 = $_POST['matkhau2'] ?? '';

    if (empty($hodem) || empty($ten) || empty($diachi) || empty($dienthoai) || empty($email) || empty($matkhau) || empty($matkhau2)) {
        $msg = "Vui lòng điền đầy đủ thông tin!";
    } elseif ($matkhau !== $matkhau2) {
        $msg = "Mật khẩu không khớp!";
    } else {
        $khachhang = new clskhachhang();
        if ($khachhang->kiemTraEmail($email)) {
            $msg = "Email đã được sử dụng!";
        } else {
            $matkhau = md5($matkhau); // Mã hóa mật khẩu bằng MD5 (lưu ý: nên dùng password_hash trong thực tế)
            $khachhang->dangKyKhachHang($hodem, $ten, $diachi, $dienthoai, $email, $matkhau);
            $msg = "Đăng ký thành công! <a href='/THUCHANHPHP/TUAN7/pages/dangnhap.php'>Đăng nhập ngay</a>";
        }
    }
}
?>

<h2>Đăng Ký</h2>
<p style="text-align: center; color: red;"><?php echo $msg; ?></p>
<form method="POST" action="">
    <table align="center" border="1">
        <tr>
            <th>Họ và đệm</th>
            <td><input type="text" name="hodem" value="<?php echo isset($_POST['hodem']) ? htmlspecialchars($_POST['hodem']) : ''; ?>"></td>
        </tr>
        <tr>
            <th>Tên</th>
            <td><input type="text" name="ten" value="<?php echo isset($_POST['ten']) ? htmlspecialchars($_POST['ten']) : ''; ?>"></td>
        </tr>
        <tr>
            <th>Địa chỉ</th>
            <td><input type="text" name="diachi" value="<?php echo isset($_POST['diachi']) ? htmlspecialchars($_POST['diachi']) : ''; ?>"></td>
        </tr>
        <tr>
            <th>Số điện thoại</th>
            <td><input type="text" name="dienthoai" value="<?php echo isset($_POST['dienthoai']) ? htmlspecialchars($_POST['dienthoai']) : ''; ?>"></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><input type="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"></td>
        </tr>
        <tr>
            <th>Mật khẩu</th>
            <td><input type="password" name="matkhau"></td>
        </tr>
        <tr>
            <th>Nhập lại mật khẩu</th>
            <td><input type="password" name="matkhau2"></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">
                <input type="submit" value="Đăng ký" class="btn">
                <a href="/THUCHANHPHP/TUAN7/index.php" class="btn">Quay lại</a>
            </td>
        </tr>
    </table>
</form>

<?php
require_once '../layout/footer.php';
?>