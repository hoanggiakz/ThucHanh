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
$conn = $db->connect();

// Lấy dữ liệu thống kê mua hàng
$sql = "SELECT DATE(dh.ngaydh) as ngaydat, SUM(ctdh.soluong) as tongsoluong 
        FROM DONHANG dh 
        JOIN CHITIETDONHANG ctdh ON dh.iddh = ctdh.iddh 
        GROUP BY DATE(dh.ngaydh)";
$stmt = $conn->prepare($sql);
$stmt->execute();
$thongke = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Chuẩn bị dữ liệu cho biểu đồ
$labels = [];
$data = [];
foreach ($thongke as $tk) {
    $labels[] = $tk['ngaydat'];
    $data[] = $tk['tongsoluong'];
}

// Chuyển dữ liệu sang JSON để sử dụng trong JavaScript
$labels_json = json_encode($labels);
$data_json = json_encode($data);
?>

<h2>Thống Kê Mua Hàng</h2>

<!-- Biểu đồ -->
<div style="width: 80%; margin: 0 auto;">
    <canvas id="thongKeChart"></canvas>
</div>

<!-- Bảng dữ liệu -->
<table align="center" border="1" style="margin-top: 20px;">
    <thead>
        <tr>
            <th>Ngày đặt hàng</th>
            <th>Tổng số lượng</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($thongke as $tk): ?>
            <tr>
                <td><?php echo htmlspecialchars($tk['ngaydat']); ?></td>
                <td><?php echo htmlspecialchars($tk['tongsoluong']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div style="text-align: center; margin-top: 20px;">
    <a href="/THUCHANHPHP/TUAN7/index.php" class="btn">Quay lại trang chủ</a>
</div>

<!-- Thêm Chart.js và mã JavaScript để vẽ biểu đồ -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script borderColor: 'rgba(255, 99, 132, 1)',
backgroundColor: 'rgba(255, 99, 132, 0.2)',>
    
    // Lấy dữ liệu từ PHP
    const labels = <?php echo $labels_json; ?>;
    const data = <?php echo $data_json; ?>;

    // Vẽ biểu đồ
    const ctx = document.getElementById('thongKeChart').getContext('2d');
    new Chart(ctx, {
        type: 'line', // Loại biểu đồ: line (đường), bar (cột), pie (tròn), v.v.
        data: {
            labels: labels, // Nhãn (ngày đặt hàng)
            datasets: [{
                label: 'Tổng số lượng',
                data: data, // Dữ liệu (tổng số lượng)
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Tổng số lượng'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Ngày đặt hàng'
                    }
                }
            }
        }
    });
    
</script>


<?php
require_once '../layout/footer.php';
?>