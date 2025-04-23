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

// Lấy danh sách công ty cho combobox
$congTys = $db->danhsachcongty_combobox();

// Lấy danh sách sản phẩm
$sql = "SELECT sp.idsp, sp.tensp, sp.gia, sp.mota, sp.hinh, sp.giamgia, ct.tencty 
        FROM SANPHAM sp 
        JOIN CONGTY ct ON sp.idcty = ct.idcty";
$stmt = $db->connect()->prepare($sql);
$stmt->execute();
$sanphams = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Xử lý xóa sản phẩm (Yêu cầu 7.1)
if (isset($_GET['txtid'])) {
    $idsp = filter_var($_GET['txtid'], FILTER_VALIDATE_INT);
    if ($idsp > 0) {
        if ($db->checkSanPhamInDonHang($idsp)) {
            $msg = "Không thể xóa sản phẩm này vì đã có trong đơn hàng!";
        } else {
            $hinh = $db->laygiatritheodieukien($idsp);
            // Chuyển đổi đường dẫn URL thành đường dẫn vật lý
            $hinhPath = $_SERVER['DOCUMENT_ROOT'] . $hinh;

            // Debug: In ra đường dẫn để kiểm tra
            echo "<!-- Debug: Hinh URL = $hinh, Hinh Path = $hinhPath, Exists = " . (file_exists($hinhPath) ? 'Yes' : 'No') . " -->";

            // Kiểm tra và xóa file hình ảnh
            if ($hinh && file_exists($hinhPath) && unlink($hinhPath)) {
                if ($db->thucthisql($idsp)) {
                    $msg = "Xóa sản phẩm thành công!";
                    echo "<script language='javascript'>window.location='quanlysanpham.php';</script>";
                } else {
                    $msg = "Xóa sản phẩm không thành công!";
                }
            } else {
                // Nếu không xóa được hình, vẫn thử xóa sản phẩm
                if ($db->thucthisql($idsp)) {
                    $msg = "Xóa sản phẩm thành công (nhưng không xóa được hình)!";
                    echo "<script language='javascript'>window.location='quanlysanpham.php';</script>";
                } else {
                    $msg = "Xóa sản phẩm không thành công!";
                }
            }
        }
    } else {
        $msg = "Vui lòng chọn sản phẩm cần xóa!";
    }
}

// Xử lý thêm hoặc sửa sản phẩm (Tuần 6 + Yêu cầu 7.2)
$action = isset($_POST['action']) ? $_POST['action'] : '';
$selectedSanPham = null;
if (isset($_GET['idsp']) && $action != 'Thêm sản phẩm') {
    $idsp = filter_var($_GET['idsp'], FILTER_VALIDATE_INT);
    if ($idsp > 0) {
        $selectedSanPham = $db->getSanPhamById($idsp);
        if (!$selectedSanPham) {
            $msg = "Sản phẩm không tồn tại!";
        }
    } else {
        $msg = "ID sản phẩm không hợp lệ!";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten = trim($_POST['txtten']);
    $gia = trim($_POST['txtgia']);
    $mota = trim($_POST['txtmota']);
    $idcty = $_POST['congty'];
    $name = $_FILES['myfile']['name'];
    $tmp_name = $_FILES['myfile']['tmp_name'];
    $giamgia = trim($_POST['txtgiamgia']);

    if (empty($ten) || empty($gia) || empty($idcty)) {
        $msg = "Vui lòng nhập đầy đủ thông tin (Tên, Giá, Công ty)!";
    } elseif ($gia < 0) {
        $msg = "Giá không được âm!";
    }elseif (!empty($giamgia) && !is_numeric($giamgia)) {
        $msg = "Giảm giá phải là số!";
    } elseif (!empty($giamgia) && $giamgia < 0) {
        $msg = "Giảm giá không được âm!";
    }else {
        if ($action == 'Thêm sản phẩm') {
            if ($name != '') {
                if ($db->uploadfile($name, $tmp_name, "../images") == 1) {
                    $hinh = "/THUCHANHPHP/TUAN7/images/" . $name;
                    if ($db->themSanPham($idcty, $ten, $gia, $mota, $hinh, $giamgia)) {
                        $msg = "Thêm sản phẩm thành công!";
                        echo "<script language='javascript'>window.location='quanlysanpham.php';</script>";
                    } else {
                        $msg = "Thêm sản phẩm không thành công!";
                    }
                } else {
                    $msg = "Upload hình không thành công!";
                }
            } else {
                $msg = "Vui lòng chọn hình ảnh!";
            }
        } elseif ($action == 'Sửa sản phẩm') {
            $idsp = filter_var($_POST['idsp'], FILTER_VALIDATE_INT);
            if ($idsp <= 0) {
                $msg = "ID sản phẩm không hợp lệ!";
            } else {
                $oldSanPham = $db->getSanPhamById($idsp);
                if (!$oldSanPham) {
                    $msg = "Sản phẩm không tồn tại!";
                } else {
                    $hinh = $oldSanPham['hinh'];

                    if ($name != '') {
                        $hinhPath = $_SERVER['DOCUMENT_ROOT'] . $hinh;
                        if (file_exists($hinhPath) && unlink($hinhPath)) {
                            if ($db->uploadfile($name, $tmp_name, "../images") == 1) {
                                $hinh = "/THUCHANHPHP/TUAN7/images/" . $name;
                            } else {
                                $msg = "Upload hình không thành công!";
                            }
                        } else {
                            $msg = "Xóa hình cũ không thành công!";
                        }
                    }

                    if ($db->suaSanPham($idsp, $idcty, $ten, $gia, $mota, $hinh, $giamgia)) {
                        $msg = "Sửa sản phẩm thành công!";
                        echo "<script language='javascript'>window.location='quanlysanpham.php';</script>";
                    } else {
                        $msg = "Sửa sản phẩm không thành công!";
                    }
                }
            }
        }
    }
}
?>

<h2>Quản lý Sản phẩm</h2>
<p
    style="text-align: center; color: <?php echo $msg == 'Thêm sản phẩm thành công!' || $msg == 'Sửa sản phẩm thành công!' ? 'green' : 'red'; ?>;">
    <?php echo $msg; ?></p>

<!-- Form thêm/sửa sản phẩm -->
<form method="POST" action="" enctype="multipart/form-data">
    <input type="hidden" name="idsp" value="<?php echo $selectedSanPham ? $selectedSanPham['idsp'] : ''; ?>">
    <table align="center" border="1">
        <tr>
            <td>Công ty cung cấp:</td>
            <td>
                <select name="congty" required>
                    <option value="">--Chọn công ty--</option>
                    <?php for ($i = 0; $i < count($congTys); $i++): ?>
                    <option value="<?php echo $congTys[$i]['idcty']; ?>"
                        <?php echo $selectedSanPham && $selectedSanPham['idcty'] == $congTys[$i]['idcty'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($congTys[$i]['tencty']); ?>
                    </option>
                    <?php endfor; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Nhập tên sản phẩm:</td>
            <td><input type="text" name="txtten"
                    value="<?php echo $selectedSanPham ? htmlspecialchars($selectedSanPham['tensp']) : ''; ?>" required>
            </td>
        </tr>
        <tr>
            <td>Nhập giá:</td>
            <td><input type="number" name="txtgia"
                    value="<?php echo $selectedSanPham ? htmlspecialchars($selectedSanPham['gia']) : ''; ?>" required
                    min="0"></td>
        </tr>
        <tr>
            <td>Mô tả:</td>
            <td><textarea name="txtmota" rows="3"
                    style="width: 100%;"><?php echo $selectedSanPham ? htmlspecialchars($selectedSanPham['mota']) : ''; ?></textarea>
            </td>
        </tr>
        <tr>
        <tr>
            <td>Giảm giá:</td>
            <td><input type="number" name="txtgiamgia"
                    value="<?php echo $selectedSanPham ? htmlspecialchars($selectedSanPham['giamgia']) : ''; ?>" min="0"
                    placeholder="Nhập giá giảm (nếu có)"></td>
        </tr>
        </tr>
        <tr>
            <td>Hình đại diện:</td>
            <td>
                <input type="file" name="myfile" accept="image/*" <?php echo !$selectedSanPham ? 'required' : ''; ?>>
                <?php if ($selectedSanPham && $selectedSanPham['hinh']): ?>
                <p>Hình hiện tại: <img src="<?php echo htmlspecialchars($selectedSanPham['hinh']); ?>"
                        alt="Hình sản phẩm" style="width: 50px; height: auto;"></p>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">
                <input type="submit" name="action" value="Thêm sản phẩm" class="btn">
                <input type="submit" name="action" value="Sửa sản phẩm" class="btn"
                    <?php echo !$selectedSanPham ? 'disabled' : ''; ?>>
            </td>
        </tr>
    </table>
</form>

<!-- Danh sách sản phẩm -->
<h3>Danh sách Sản phẩm</h3>
<?php if (count($sanphams) > 0): ?>
<table align="center" border="1">
    <thead>
        <tr>
            <th>Mã SP</th>
            <th>Hình ảnh</th>
            <th>Tên sản phẩm</th>
            <th>Giá</th>
            <th>Giảm giá</th>
            <th>Mô tả</th>
            <th>Thương hiệu</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($sanphams as $sp): ?>
        <tr>
            <td><?php echo htmlspecialchars($sp['idsp']); ?></td>
            <td>
                <?php if ($sp['hinh'] && file_exists($_SERVER['DOCUMENT_ROOT'] . $sp['hinh'])): ?>
                <img src="<?php echo htmlspecialchars($sp['hinh']); ?>"
                    alt="<?php echo htmlspecialchars($sp['tensp']); ?>" style="width: 50px; height: auto;">
                <?php else: ?>
                <span>Không có hình ảnh</span>
                <?php endif; ?>
            </td>
            <td><?php echo htmlspecialchars($sp['tensp']); ?></td>
            <td><?php echo number_format($sp['gia'], 0, ',', '.') . ' VNĐ'; ?></td>
            <td><?php echo $sp['giamgia'] ? number_format($sp['giamgia'], 0, ',', '.') . ' VNĐ' : 'Không có'; ?></td>
            <td><?php echo htmlspecialchars($sp['mota']); ?></td>
            <td><?php echo htmlspecialchars($sp['tencty']); ?></td>
            <td>
                <a href="/THUCHANHPHP/TUAN7/pages/quanlysanpham.php?idsp=<?php echo $sp['idsp']; ?>" class="btn">Sửa</a>
                <a href="/THUCHANHPHP/TUAN7/pages/quanlysanpham.php?txtid=<?php echo $sp['idsp']; ?>" class="btn"
                    onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">Xóa</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
<p style="text-align: center;">Chưa có sản phẩm nào!</p>
<?php endif; ?>

<div style="text-align: center; margin-top: 20px;">
    <a href="/THUCHANHPHP/TUAN7/index.php" class="btn">Quay lại trang chủ</a>
</div>

<?php
require_once '../layout/footer.php';
?>