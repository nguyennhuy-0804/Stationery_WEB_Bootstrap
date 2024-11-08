<?php 
session_start(); 
include "database/conn.php"; 

// lấy dữ liệu thống kê 
$statistics = [];
$sql = "SELECT MONTH(NgayHD) AS month, 
               SUM(Trigia) AS total_revenue,
               COUNT(DISTINCT MaTV) AS total_customers
        FROM donhang
        GROUP BY MONTH(NgayHD)";
if ($result = $conn->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        $statistics[] = $row;
    }
    $result->free(); // đổ kết quả
}

// Tính tổng doanh thu cho từng sản phẩm 
$product_revenue_sql = "SELECT p.TenSP, 
                               SUM(dh.Trigia) AS product_revenue
                        FROM donhang dh
                        JOIN chitietdonhang ctdh ON dh.MaHD = ctdh.MaHD
                        JOIN sanpham p ON ctdh.MaSP = p.MaSP
                        GROUP BY p.TenSP
                        ORDER BY product_revenue DESC
                        LIMIT 3";
$product_revenue_result = $conn->query($product_revenue_sql);

// Lưu doanh thu hàng tháng và các sản phẩm hàng đầu vào các biến để sử dụng cho biểu đồ
$months = [];
$revenues = [];
foreach ($statistics as $stat) {
    $months[] = $stat['month'];
    $revenues[] = $stat['total_revenue'];
}

// Lưu tên và doanh thu của 3 sản phẩm hàng đầu
$top_products = [];
$top_revenues = [];
if ($product_revenue_result) {
    while ($row = $product_revenue_result->fetch_assoc()) {
        $top_products[] = $row['TenSP'];
        $top_revenues[] = $row['product_revenue'];
    }
    $product_revenue_result->free(); 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Thống kê</title>

    <!-- Libraries -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- CSS -->
    <link rel="stylesheet" href="css/main.css" />
    <link rel="stylesheet" href="css/reset.css" />
    <link rel="stylesheet" href="css/styles.css" />
    <link rel="stylesheet" href="css/layouts/header.css" />
    <link rel="stylesheet" href="css/layouts/footer.css" />



    <style>
        .table {
            width: 95%;
            table-layout: fixed;
            border-collapse: collapse; /* Gộp viền để có cái nhìn sạch sẽ hơn */
            border: 3px solid #F26F33; /* Viền ngoài màu cam */
            overflow: hidden; /* Ngăn ngừa tràn góc */
            postion: center; 
            margin-left: 15px;       
        }
        .table th,
        .table td {
            border: 1px solid #dee2e6; /* Đường viền màu xám nhạt cho ô bảng */
            padding: 12px; /* Khoảng cách cho ô bảng */
            text-align: center; /* Căn giữa văn bản */
        }

        .table th {
            background-color: #f8f9fa; /* Màu nền nhạt cho tiêu đề bảng */
            color: #343a40; /* Màu chữ tối cho tiêu đề bảng */
            font-weight: bold; /* Làm cho văn bản tiêu đề in đậm */
        }

        /* Căn giữa tiêu đề */
        h3 {
            text-align: center; /* Căn giữa tiêu đề phụ */
        }

        /* Các kiểu cho vùng chứa biểu đồ */
        .chart-container {
            position: relative;
            margin: auto;
            width: 80%; /* Điều chỉnh chiều rộng theo nhu cầu */
            height: 400px; /* Điều chỉnh chiều cao theo nhu cầu */
            border: 2px solid #005F69; /* Đường viền tối màu xung quanh biểu đồ */
            border-radius: 8px; /* Góc bo tròn */
            background-color: #ffffff; /* Màu nền trắng */
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); /* Đổ bóng nhẹ để tạo chiều sâu */
        }

        /* Các kiểu cho tiêu đề */
        h1 {
            color: #343a40; /* Màu chữ tối cho tiêu đề */
            text-align: center; /* Căn giữa tiêu đề */
            margin-left: 5px; /* Căn giữa tiêu đề */
        }

        h2 {
            margin-top: 40px; /* Khoảng cách phía trên tiêu đề phần */
            margin-bottom: 20px; /* Khoảng cách phía dưới tiêu đề phần */
            text-align: left; /* Căn trái tiêu đề */
            margin-left: 15px; /* Khoảng cách bên trái */
        }

        h5 {
            text-align: center; /* Căn giữa tiêu đề phụ */
            margin-top: 20px; /* Khoảng cách phía trên tiêu đề phụ */
            margin-bottom: 20px; /* Khoảng cách phía dưới tiêu đề phụ */
            font-style: italic; /* Đặt kiểu chữ nghiêng cho tiêu đề phụ */
        }

        /* Màu chữ tùy chỉnh cho bảng */
        .table td {
            color: #495057; /* Màu chữ tối hơn cho dữ liệu bảng */
        }

        h5:hover {
            color: #007bff; /* Màu chữ xanh dương khi hover */
        }
    </style>
</head>

<body>
    <!-- Header -->
    <?php include 'layouts/AdminHeader.php'; ?>
    <h2>Thống kê doanh thu</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Tháng</th>
                <th>Tổng doanh thu</th>
                <th>Tổng số lượng người mua</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($statistics as $stat): ?>
                <tr>
                    <td><?php echo $stat['month']; ?></td>
                    <td><?php echo number_format($stat['total_revenue'], 0, ',', '.'); ?> VNĐ</td>
                    <td><?php echo $stat['total_customers']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Charts section -->
    <div>
        
        <div class="chart-container">
            <canvas id="monthlyRevenueChart"></canvas>
        </div>
        <h5>Biểu đồ doanh thu từng tháng</h5>

    </div>

    <h2>Doanh thu theo từng loại sản phẩm</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Tên sản phẩm</th>
                <th>Doanh thu</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($top_products as $index => $product): ?>
                <tr>
                    <td><?php echo $product; ?></td>
                    <td><?php echo number_format($top_revenues[$index], 0, ',', '.'); ?> VNĐ</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    
    <div>
        
        <div class="chart-container">
            <canvas id="topProductsChart"></canvas>
        </div>
        <h5>Biểu đồ 3 sản phẩm bán chạy nhất</h5>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Biểu đồ doanh thu hàng tháng 
        const ctx1 = document.getElementById('monthlyRevenueChart').getContext('2d');
        const monthlyRevenueChart = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($months); ?>,
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: <?php echo json_encode($revenues); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.5)', // Màu nền nhẹ
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Để tự động điều chỉnh theo kích thước
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Doanh thu (VNĐ)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Tháng'
                        }
                    }
                }
            }
        });

        // Biểu đồ doanh thu theo sản phẩm 
        const ctx2 = document.getElementById('topProductsChart').getContext('2d');
        const topProductsChart = new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($top_products); ?>,
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: <?php echo json_encode($top_revenues); ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 206, 86, 0.5)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Để tự động điều chỉnh theo kích thước
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw.toLocaleString() + ' VNĐ';
                            }
                        }
                    }
                }
            }
        });
    </script>

    <!-- Footer -->
    <?php include 'layouts/AdminFooter.php'; ?>

    <!-- Scripts -->
    <script src="scripts/header.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
</body>
</html>

<?php
$conn->close(); // Đóng kết nối
?>
