<?php
ob_start();
require_once '../layout/header.php';
require_once '../class/clskhachhang.php';

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $matkhau = $_POST['matkhau'] ?? '';

    if (empty($email) || empty($matkhau)) {
        $msg = "Vui lòng điền đầy đủ thông tin!";
    } else {
        $khachhang = new clskhachhang();
        $matkhau = md5($matkhau); // Mã hóa mật khẩu bằng MD5
        if ($email === 'admin@gmail.com' && $matkhau === md5('admin123')) {
            $_SESSION['admin'] = true;
            header("Location: /THUCHANHPHP/TUAN7/index.php");
            exit();
        } elseif ($khachhang->dangnhap($email, $matkhau)) {
            header("Location:/THUCHANHPHP/TUAN7/index.php");
            exit();
        } else {
            $msg = "Email hoặc mật khẩu không đúng!";
        }
    }
}
?>

<h2>Đăng Nhập</h2>
<p style="text-align: center; color: red;"><?php echo $msg; ?></p>
<form method="POST" action="">
    <table align="center" border="1">
        <tr>
            <th>Email</th>
            <td><input type="email" name="email"
                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"></td>
        </tr>
        <tr>
            <th>Mật khẩu</th>
            <td><input type="password" name="matkhau"></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">
                <input type="submit" value="Đăng nhập" class="btn">
                <a href="/THUCHANHPHP/TUAN7/index.php" class="btn">Quay lại</a>
            </td>
        </tr>
    </table>
</form>

<?php
require_once '../layout/footer.php';
?>