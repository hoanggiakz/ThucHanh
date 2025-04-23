<?php
require_once '../layout/header.php';
require_once '../class/csdltmdt.php';

// Kiểm tra đăng nhập admin
if (!isset($_SESSION['admin'])) {
    echo "<p style='text-align: center; color: red;'>Bạn cần đăng nhập với tài khoản admin để truy cập trang này! <a href='/THUCHANHPHP/TUAN7/pages/dangnhap.php'>Đăng nhập ngay</a></p>";
    require_once '../layout/footer.php';
    exit();
}

$db = new csdltmdt();
$msg = '';

if (!isset($_GET['idkh'])) {
    echo "<p style='text-align: center; color: red;'>Không tìm thấy khách hàng!</p>";
    require_once '../layout/footer.php';
    exit();
}

$idkh = filter_var($_GET['idkh'], FILTER_VALIDATE_INT);
if ($idkh <= 0) {
    echo "<p style='text-align: center; color: red;'>ID khách hàng không hợp lệ!</p>";
    require_once '../layout/footer.php';
    exit();
}

$khachhang = $db->getKhachHangById($idkh);
if (!$khachhang) {
    echo "<p style='text-align: center; color: red;'>Khách hàng không tồn tại!</p>";
    require_once '../layout/footer.php';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tenkh = trim($_POST['tenkh']);
    $diachi = trim($_POST['diachi']);
    $sdt = trim($_POST['sdt']);
    $email = trim($_POST['email']);
    $matkhau = trim($_POST['matkhau']);

    if (empty($tenkh) || empty($diachi) || empty($sdt) || empty($email) || empty($matkhau)) {
        $msg = "Vui lòng nhập đầy đủ thông tin!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "Email không hợp lệ!";
    } else {
        if ($email !== $khachhang['email'] && $db->checkEmail($email)) {
            $msg = "Email đã tồn tại!";
        } else {
            if ($db->capNhatKhachHang($idkh, $tenkh, $diachi, $sdt, $email, $matkhau)) {
                $msg = "Cập nhật thông tin khách hàng thành công!";
                echo "<script language='javascript'>window.location='quanlykhachhang.php';</script>";
            } else {
                $msg = "Cập nhật thông tin khách hàng không thành công!";
            }
        }
    }
}
?>

<h2>Chỉnh Sửa Thông Tin Khách Hàng</h2>
<p style="text-align: center; color: <?php echo $msg == 'Cập nhật thông tin khách hàng thành công!' ? 'green' : 'red'; ?>;"><?php echo $msg; ?></p>

<form method="POST" action="">
    <table align="center" border="1">
        <tr>
            <td>Tên khách hàng:</td>
            <td><input type="text" name="tenkh" value="<?php echo htmlspecialchars($khachhang['tenkh']); ?>" required></td>
        </tr>
        <tr>
            <td>Địa chỉ:</td>
            <td><input type="text" name="diachi" value="<?php echo htmlspecialchars($khachhang['diachi']); ?>" required></td>
        </tr>
        <tr>
            <td>Số điện thoại:</td>
            <td><input type="text" name="sdt" value="<?php echo htmlspecialchars($khachhang['sdt']); ?>" required></td>
        </tr>
        <tr>
            <td>Email:</td>
            <td><input type="email" name="email" value="<?php echo htmlspecialchars($khachhang['email']); ?>" required></td>
        </tr>
        <tr>
            <td>Mật khẩu:</td>
            <td><input type="password" name="matkhau" value="<?php echo htmlspecialchars($khachhang['matkhau']); ?>" required></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">
                <input type="submit" value="Cập nhật" class="btn">
            </td>
        </tr>
    </table>
</form>

<div style="text-align: center; margin-top: 20px;">
    <a href="/THUCHANHPHP/TUAN7/pages/quanlykhachhang.php" class="btn">Quay lại</a>
</div>

<?php
require_once '../layout/footer.php';
?>