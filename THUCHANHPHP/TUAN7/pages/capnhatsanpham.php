<?php
require_once '../layout/header.php';
require_once '../class/csdltmdt.php';

// Kiểm tra đăng nhập admin
if (!isset($_SESSION['admin'])) {
    echo "<p style='text-align: center; color: red;'>Bạn cần đăng nhập với tài khoản admin để truy cập trang này! <a href='/THUCHANHPHP/TUAN7/pages/dangnhap.php'>Đăng nhập ngay</a></p>";
    require_once '../layout/footer.php';
    exit();
}

// Kiểm tra idsp từ query string
if (!isset($_GET['idsp']) || !filter_var($_GET['idsp'], FILTER_VALIDATE_INT)) {
    echo "<p style='text-align: center; color: red;'>Mã sản phẩm không hợp lệ!</p>";
    require_once '../layout/footer.php';
    exit();
}

$idsp = $_GET['idsp'];
$db = new csdltmdt();

// Lấy thông tin sản phẩm
$sql = "SELECT * FROM SANPHAM WHERE idsp = ?";
$stmt = $db->connect()->prepare($sql);
$stmt->execute([$idsp]);
$sanpham = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$sanpham) {
    echo "<p style='text-align: center; color: red;'>Sản phẩm không tồn tại!</p>";
    require_once '../layout/footer.php';
    exit();
}

// Lấy danh sách công ty
$sql = "SELECT idcty, tencty FROM CONGTY";
$stmt = $db->connect()->prepare($sql);
$stmt->execute();
$congty = $stmt->fetchAll(PDO::FETCH_ASSOC);

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tensp = $_POST['tensp'] ?? '';
    $gia = $_POST['gia'] ?? '';
    $mota = $_POST['mota'] ?? '';
    $idcty = $_POST['idcty'] ?? '';
    $giamgia = $_POST['giamgia'] ?? '';
    $hinh = $sanpham['hinh']; // Giữ hình ảnh cũ nếu không upload hình mới

    // Xử lý upload hình ảnh
    if (isset($_FILES['hinh']) && $_FILES['hinh']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/THUCHANHPHP/TUAN7/images/';
        $fileName = basename($_FILES['hinh']['name']);
        $uploadFile = $uploadDir . $fileName;

        // Kiểm tra loại file
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($_FILES['hinh']['tmp_name']);
        if (!in_array($fileType, $allowedTypes)) {
            $msg = "Chỉ cho phép upload file hình ảnh (JPEG, PNG, GIF)!";
        } elseif (move_uploaded_file($_FILES['hinh']['tmp_name'], $uploadFile)) {
            // Xóa hình ảnh cũ nếu có
            if (!empty($hinh) && file_exists($uploadDir . $hinh)) {
                unlink($uploadDir . $hinh);
            }
            $hinh = $fileName; // Cập nhật tên file mới
        } else {
            $msg = "Lỗi khi upload hình ảnh!";
        }
    }

    if (empty($tensp) || empty($gia) || empty($idcty)) {
        $msg = "Vui lòng điền đầy đủ thông tin!";
    } elseif (!empty($giamgia) && !is_numeric($giamgia)) {
        $msg = "Giảm giá phải là số!";
    } elseif (!empty($giamgia) && $giamgia < 0) {
        $msg = "Giảm giá không được âm!";
    }
    
    else {
        try {
            $sql = "UPDATE SANPHAM SET tensp = ?, gia = ?, mota = ?, giamgia = ?, hinh = ?, idcty = ? WHERE idsp = ?";
            $stmt = $db->connect()->prepare($sql);
            $stmt->execute([$tensp, $gia, $mota, $giamgia, $hinh, $idcty, $idsp]);
            $msg = "Cập nhật sản phẩm thành công!";
        } catch (Exception $e) {
            $msg = "Lỗi: " . $e->getMessage();
        }
    }
}
?>

<h2>Cập Nhật Sản Phẩm</h2>
<p style="text-align: center; color: green;"><?php echo $msg; ?></p>
<form method="POST" action="" enctype="multipart/form-data">
    <table align="center" border="1">
        <tr>
            <th>Mã sản phẩm</th>
            <td><input type="text" value="<?php echo htmlspecialchars($sanpham['idsp']); ?>" disabled></td>
        </tr>
        <tr>
            <th>Tên sản phẩm</th>
            <td><input type="text" name="tensp" value="<?php echo htmlspecialchars($sanpham['tensp']); ?>" required>
            </td>
        </tr>
        <tr>
            <th>Giá</th>
            <td><input type="number" name="gia" min="0" value="<?php echo htmlspecialchars($sanpham['gia']); ?>"
                    required></td>
        </tr>
        <tr>
            <th>Mô tả</th>
            <td><textarea name="mota"><?php echo htmlspecialchars($sanpham['mota'] ?? ''); ?></textarea></td>
        </tr>
        <tr>
            <th>Giảm giá</th>
            <td><input type="number" name="giamgia" value="<?php echo htmlspecialchars($sanpham['giamgia'] ?? ''); ?>"
                    min="0" placeholder="Nhập giá giảm (nếu có)"></td>
        </tr>
        <tr>
            <th>Hình ảnh hiện tại</th>
            <td>
                <?php if (!empty($sanpham['hinh'])): ?>
                <img src="/THUCHANHPHP/TUAN7/images/<?php echo htmlspecialchars($sanpham['hinh']); ?>"
                    alt="<?php echo htmlspecialchars($sanpham['tensp']); ?>" width="80">
                <?php else: ?>
                <span>Không có hình ảnh</span>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <th>Upload hình ảnh mới</th>
            <td><input type="file" name="hinh" accept="image/*"></td>
        </tr>
        <tr>
            <th>Công ty</th>
            <td>
                <select name="idcty" required>
                    <option value="">-- Chọn công ty --</option>
                    <?php foreach ($congty as $cty): ?>
                    <option value="<?php echo htmlspecialchars($cty['idcty']); ?>"
                        <?php echo $cty['idcty'] == $sanpham['idcty'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cty['tencty']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">
                <input type="submit" value="Cập nhật sản phẩm" class="btn">
                <a href="/THUCHANHPHP/TUAN7/pages/quanlysanpham.php" class="btn">Quay lại</a>
            </td>
        </tr>
    </table>
</form>

<?php
require_once '../layout/footer.php';
?>