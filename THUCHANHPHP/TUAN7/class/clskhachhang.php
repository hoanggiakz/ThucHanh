<?php
class clskhachhang {
    private $host = "localhost";
    private $user = "root";         // Sử dụng tài khoản root mặc định
    private $pass = "";             // Không có mật khẩu (nếu bạn dùng XAMPP/Laragon)
    private $db = "tmdt_db3";       // Cơ sở dữ liệu bạn đang dùng

    // Phương thức kết nối cơ sở dữ liệu
    public function connect() {
        try {
            $dsn = "mysql:host=$this->host;dbname=$this->db;charset=utf8";
            $conn = new PDO($dsn, $this->user, $this->pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            die("❌ Kết nối thất bại: " . $e->getMessage());
        }
    }

    // Đăng ký khách hàng
    public function dangKyKhachHang($hodem, $ten, $diachi, $dienthoai, $email, $matkhau) {
        $link = $this->connect();
        $sql = "INSERT INTO KHACHHANG (hodem, ten, diachi, dienthoai, email, matkhau) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $link->prepare($sql);
        $stmt->execute([$hodem, $ten, $diachi, $dienthoai, $email, $matkhau]);
        return $link->lastInsertId();
    }

    // Đăng nhập khách hàng
    public function dangnhap($email, $matkhau) {
        session_start(); // Đảm bảo có session
        $link = $this->connect();
        $sql = "SELECT idkh, CONCAT(hodem, ' ', ten) as tenkh, matkhau 
                FROM KHACHHANG 
                WHERE email = ? AND matkhau = ?";
        $stmt = $link->prepare($sql);
        $stmt->execute([$email, $matkhau]);
        $kh = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($kh) {
            $_SESSION['idkh'] = $kh['idkh'];
            $_SESSION['tenkh'] = $kh['tenkh'];
            return true;
        }
        return false;
    }

    // Lấy danh sách khách hàng
    public function getAllKhachHang() {
        $link = $this->connect();
        $sql = "SELECT idkh, CONCAT(hodem, ' ', ten) as tenkh, diachi, dienthoai, email 
                FROM KHACHHANG";
        $stmt = $link->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy thông tin khách hàng theo ID
    public function getKhachHangById($idkh) {
        $link = $this->connect();
        $sql = "SELECT idkh, hodem, ten, diachi, dienthoai, email 
                FROM KHACHHANG 
                WHERE idkh = ?";
        $stmt = $link->prepare($sql);
        $stmt->execute([$idkh]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Cập nhật thông tin khách hàng
    public function capNhatKhachHang($idkh, $hodem, $ten, $diachi, $dienthoai, $email) {
        $link = $this->connect();
        $sql = "UPDATE KHACHHANG 
                SET hodem = ?, ten = ?, diachi = ?, dienthoai = ?, email = ? 
                WHERE idkh = ?";
        $stmt = $link->prepare($sql);
        $stmt->execute([$hodem, $ten, $diachi, $dienthoai, $email, $idkh]);
        return true;
    }

    // Xóa khách hàng
    public function xoaKhachHang($idkh) {
        $link = $this->connect();
        $sql = "SELECT COUNT(*) FROM DONHANG WHERE idkh = ?";
        $stmt = $link->prepare($sql);
        $stmt->execute([$idkh]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            return false; // Không thể xóa vì có đơn hàng
        }

        $sql = "DELETE FROM KHACHHANG WHERE idkh = ?";
        $stmt = $link->prepare($sql);
        $stmt->execute([$idkh]);
        return true;
    }

    // Kiểm tra email đã tồn tại
    public function kiemTraEmail($email) {
        $link = $this->connect();
        $sql = "SELECT COUNT(*) FROM KHACHHANG WHERE email = ?";
        $stmt = $link->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }
}
?>
