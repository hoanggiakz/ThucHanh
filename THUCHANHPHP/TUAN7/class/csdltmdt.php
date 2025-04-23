<?php
require_once dirname(__DIR__) . '/config.php';

class csdltmdt {
    private $con;

    public function connect() {
        try {
            $this->con = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->con->exec("SET CHARACTER SET utf8");
            $this->con->query("SET NAMES 'utf8'");
        } catch (PDOException $e) {
            echo "Không kết nối được CSDL: " . $e->getMessage();
            exit();
        }
        return $this->con;
    }

    // Lấy sản phẩm theo ID
    public function getSanPhamById($idsp) {
        $link = $this->connect();
        $sql = "SELECT * FROM SANPHAM WHERE idsp = ?";
        $stmt = $link->prepare($sql);
        $stmt->execute([$idsp]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Kiểm tra sản phẩm có trong đơn hàng không
    public function checkSanPhamInDonHang($idsp) {
        $sql = "SELECT COUNT(*) FROM CHITIETDONHANG WHERE idsp = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$idsp]);
        return $stmt->fetchColumn() > 0;
    }

    // Lấy danh sách công ty cho combobox
    public function danhsachcongty_combobox() {
        return $this->xuatdulieu("SELECT * FROM CONGTY ORDER BY tencty ASC");
    }

    // Phương thức xuất dữ liệu
    private function xuatdulieu($sql) {
        $link = $this->connect();
        $stmt = $link->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Phương thức upload file
    public function uploadfile($name, $tmp_name, $folder) {
        if ($name != '' && $tmp_name != '' && $folder != '') {
            $newname = $folder . "/" . $name;
            if (move_uploaded_file($tmp_name, $newname)) {
                return 1;
            } else {
                return 0;
            }
        }
        return 0;
    }

    // Thêm sản phẩm mới
    public function themSanPham($idcty, $tensp, $gia, $mota, $hinh) {
        $link = $this->connect();
        $sql = "INSERT INTO SANPHAM (idcty, tensp, gia, mota, hinh) VALUES (?, ?, ?, ?, ?)";
        $stmt = $link->prepare($sql);
        return $stmt->execute([$idcty, $tensp, $gia, $mota, $hinh]);
    }

    // Sửa sản phẩm
    public function suaSanPham($idsp, $idcty, $tensp, $gia, $mota, $hinh) {
        $link = $this->connect();
        $sql = "UPDATE SANPHAM SET tensp = ?, gia = ?, mota = ?, idcty = ?, hinh = ? WHERE idsp = ?";
        $stmt = $link->prepare($sql);
        return $stmt->execute([$tensp, $gia, $mota, $idcty, $hinh, $idsp]);
    }

    // Lấy hình sản phẩm theo idsp
    public function laygiatritheodieukien($idsp) {
        $sql = "SELECT hinh FROM SANPHAM WHERE idsp = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$idsp]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['hinh'] : null;
    }
    // Xóa sản phẩm
    public function thucthisql($idsp) {
        $link = $this->connect();
        $sql = "DELETE FROM SANPHAM WHERE idsp = ? LIMIT 1";
        $stmt = $link->prepare($sql);
        return $stmt->execute([$idsp]);
    }

    // Thống kê mua hàng
    public function thongkemua() {
        $link = $this->connect();
        $sql = "SELECT dh.ngaydh, SUM(ctdh.soluong) as tongsoluong 
                FROM DONHANG dh 
                LEFT JOIN CHITIETDONHANG ctdh ON dh.iddh = ctdh.iddh 
                GROUP BY dh.ngaydh";
        $stmt = $link->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Thêm đơn hàng
    public function themDonHang($idkh, $idsp, $soluong, $ngaydh, $trangthai) {
        $link = $this->connect();
        $sql = "INSERT INTO DONHANG (idkh, idsp, soluong, ngaydh, trangthai) VALUES (?, ?, ?, ?, ?)";
        $stmt = $link->prepare($sql);
        return $stmt->execute([$idkh, $idsp, $soluong, $ngaydh, $trangthai]);
    }

    // Lấy danh sách đơn hàng của khách hàng
    public function getDonHangByKhachHang($idkh) {
        $sql = "SELECT dh.iddh, dh.ngaydh, dh.trangthai, ctdh.soluong, sp.tensp, sp.gia, sp.hinh 
                FROM DONHANG dh 
                JOIN CHITIETDONHANG ctdh ON dh.iddh = ctdh.iddh 
                JOIN SANPHAM sp ON ctdh.idsp = sp.idsp 
                WHERE dh.idkh = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$idkh]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy tất cả đơn hàng (cho admin)
    public function getAllDonHang() {
        $link = $this->connect();
        $sql = "SELECT dh.iddh, dh.idkh, dh.ngaydh, dh.trangthai, SUM(ctdh.soluong) as soluong, 
                CONCAT(kh.hodem, ' ', kh.ten) as tenkh 
                FROM DONHANG dh 
                JOIN KHACHHANG kh ON dh.idkh = kh.idkh 
                LEFT JOIN CHITIETDONHANG ctdh ON dh.iddh = ctdh.iddh 
                GROUP BY dh.iddh";
        $stmt = $link->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Cập nhật trạng thái đơn hàng
    public function capNhatDonHang($iddh, $trangthai) {
        $link = $this->connect();
        $sql = "UPDATE DONHANG SET trangthai = ? WHERE iddh = ?";
        $stmt = $link->prepare($sql);
        return $stmt->execute([$trangthai, $iddh]);
    }

    // Lấy thông tin khách hàng theo ID
    public function getKhachHangById($idkh) {
        $link = $this->connect();
        $sql = "SELECT * FROM KHACHHANG WHERE idkh = ?";
        $stmt = $link->prepare($sql);
        $stmt->execute([$idkh]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Cập nhật thông tin khách hàng
    public function capNhatKhachHang($idkh, $tenkh, $diachi, $sdt, $email, $matkhau) {
        $link = $this->connect();
        $sql = "UPDATE KHACHHANG SET tenkh = ?, diachi = ?, sdt = ?, email = ?, matkhau = ? WHERE idkh = ?";
        $stmt = $link->prepare($sql);
        return $stmt->execute([$tenkh, $diachi, $sdt, $email, $matkhau, $idkh]);
    }

    // Xóa khách hàng
    public function xoaKhachHang($idkh) {
        $link = $this->connect();
        $sql = "DELETE FROM KHACHHANG WHERE idkh = ? LIMIT 1";
        $stmt = $link->prepare($sql);
        return $stmt->execute([$idkh]);
    }

    // Lấy tất cả khách hàng (cho admin)
    public function getAllKhachHang() {
        return $this->xuatdulieu("SELECT * FROM KHACHHANG");
    }

    // Đăng ký khách hàng mới
    public function dangKyKhachHang($tenkh, $diachi, $sdt, $email, $matkhau) {
        $link = $this->connect();
        $sql = "INSERT INTO KHACHHANG (tenkh, diachi, sdt, email, matkhau) VALUES (?, ?, ?, ?, ?)";
        $stmt = $link->prepare($sql);
        return $stmt->execute([$tenkh, $diachi, $sdt, $email, $matkhau]);
    }

    // Kiểm tra email đã tồn tại
    public function checkEmail($email) {
        $link = $this->connect();
        $sql = "SELECT COUNT(*) FROM KHACHHANG WHERE email = ?";
        $stmt = $link->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }
}
?>