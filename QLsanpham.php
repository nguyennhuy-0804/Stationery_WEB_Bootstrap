<?php
session_start(); // Bắt đầu phiên
include "database/conn.php"; // Kết nối đến cơ sở dữ liệu
$message = "";
// Xử lý thêm sản phẩm
if (isset($_POST['add_product'])) {
    // Kiểm tra sự tồn tại của biến trong mảng $_POST
    $MASP = $_POST['MaSP'] ?? null;
    $MALOAI = $_POST['Maloai'] ?? null;
    $TenSP = $_POST['TenSP'] ?? null;
    $Giagoc = $_POST['Giagoc'] ?? null;
    $Giaban = $_POST['Giaban'] ?? null;
    $GiaKM = $_POST['GiaKM'] ?? null; // Tùy chọn
    $MaKM = $_POST['MaKM'] ?? null; // Tùy chọn
    $Mota = $_POST['Mota'] ?? null;
    $TinhtrangTK = $_POST['TinhtrangTK'] ?? null;
    $HotTrend = isset($_POST['HotTrend']) ? 1 : 0; // Tùy chọn
    $BestSeller = isset($_POST['BestSeller']) ? 1 : 0; // Tùy chọn
    $IsClick = $_POST['IsClick'] ?? null;
    $IsLike = $_POST['IsLike'] ?? null;
    $Hinhanh = $_FILES['Hinhanh']['name'] ?? null; // Xử lý tải lên tệp
    $Ngaycapnhat = $_POST['Ngaycapnhat'] ?? null;


    // Kiểm tra mã sản phẩm và tên sản phẩm đã tồn tại
    $check_duplicate_code = "SELECT MaSP FROM sanpham WHERE MaSP = '$MASP'";
    $check_duplicate_name = "SELECT TenSP FROM sanpham WHERE TenSP = '$TenSP'";
    $result_code = $conn->query($check_duplicate_code);
    $result_name = $conn->query($check_duplicate_name);


    if ($result_code->num_rows > 0) {
        $message = "Mã sản phẩm đã tồn tại. Vui lòng sử dụng mã khác.";
    } elseif ($result_name->num_rows > 0) {
        $message = "Tên sản phẩm đã tồn tại. Vui lòng sử dụng tên khác.";
    } else {
        // Chỉ chạy câu lệnh SQL nếu các biến bắt buộc đã được xác định
        if ($MASP && $TenSP && $Giagoc && $Giaban && $Ngaycapnhat) {

            // Chèn vào cơ sở dữ liệu
            $sql = "INSERT INTO sanpham (MaSP, TenSP, Maloai, Giagoc, Giaban, GiaKM, MaKM, Mota, TinhtrangTK, HotTrend, BestSeller, IsClick, IsLike, Hinhanh, Ngaycapnhat)
                            VALUES ('$MASP', '$TenSP', '$MALOAI', '$Giagoc', '$Giaban', '$GiaKM', '$MaKM', '$Mota', '$TinhtrangTK', '$HotTrend', '$BestSeller', '$IsClick', '$IsLike', '$Hinhanh', '$Ngaycapnhat')";
            mysqli_query($conn, $sql);
        } else {
            $message = "Vui lòng điền tất cả các trường bắt buộc.";
        }
    }
}


// Sửa sản phẩm
if (isset($_POST['edit_product'])) {


    // Lấy thông tin từ form
    $MASP = $_POST['MaSP'] ?? null;
    $TenSP = $_POST['TenSP'] ?? null;
    $Giaban = $_POST['Giaban'] ?? null;
    $Mota = $_POST['Mota'] ?? null;


    if ($MASP) {
        $sql = "UPDATE sanpham SET TenSP=?, Giaban=?, Mota=? WHERE MaSP=?";
        $stmt = $conn->prepare($sql);
        // Chỉnh sửa bind_param cho đúng kiểu dữ liệu
        $stmt->bind_param("siss", $TenSP, $Giaban, $Mota, $MASP);


        if ($stmt->execute()) {
            $message = "Sản phẩm đã được cập nhật thành công!";
        } else {
            $message = "Lỗi: " . $stmt->error;
        }
    } else {
        $message = "Sản phẩm không hợp lệ.";
    }
}


// Xử lý xóa sản phẩm
if (isset($_POST['delete_product'])) {
    $id = $_POST['id']; // Nhận mã sản phẩm từ biểu mẫu


    //  Xóa các bản ghi trong bảng chitietdonhang liên quan đến sản phẩm
    $sql_delete_order_details = "DELETE FROM chitietdonhang WHERE MaSP='$id'";
    if ($conn->query($sql_delete_order_details) === TRUE) {
        // Xóa các bản ghi trong bảng chitietgiohang liên quan đến sản phẩm
        $sql_delete_cart_details = "DELETE FROM chitietgiohang WHERE MaSP='$id'";
        if ($conn->query($sql_delete_cart_details) === TRUE) {
            // Xóa các bản ghi trong bảng danhgia liên quan đến sản phẩm
            $sql_delete_reviews = "DELETE FROM danhgia WHERE MaSP='$id'";
            if ($conn->query($sql_delete_reviews) === TRUE) {
                // Cuối cùng, xóa sản phẩm khỏi bảng sanpham
                $sql_delete_product = "DELETE FROM sanpham WHERE MaSP='$id'";
                if ($conn->query($sql_delete_product) === TRUE) {
                    $message = "Sản phẩm đã được xóa thành công từ tất cả các bảng!";
                } else {
                    $message = "Lỗi khi xóa sản phẩm: " . $conn->error;
                }
            } else {
                $message = "Lỗi khi xóa đánh giá: " . $conn->error;
            }
        } else {
            $message = "Lỗi khi xóa chi tiết giỏ hàng: " . $conn->error;
        }
    } else {
        $message = "Lỗi khi xóa chi tiết đơn hàng: " . $conn->error;
    }
}




// Lấy danh sách sản phẩm
$sql = "SELECT * FROM sanpham";
$result = $conn->query($sql);
?>






<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Sản Phẩm</title>


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

        button:hover {
            background: #218838;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-left: 5px;
            /* Điều chỉnh khoảng cách bên phải */
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background: #f8f9fa;
            text-align: center;
        }

        .message {
            display: block;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        /* CSS cho modal */
        .modal {
            display: none;
            /* Ẩn modal mặc định */
            position: fixed;
            /* Đặt modal ở vị trí cố định */
            z-index: 1000;
            /* Đảm bảo modal nằm trên tất cả các phần tử khác */
            left: 0;
            top: 0;
            width: 100%;
            /* Toàn bộ chiều rộng */
            height: 100%;
            /* Toàn bộ chiều cao */
            overflow: auto;
            /* Bật cuộn nếu cần */
            background-color: rgba(0, 0, 0, 0.4);
            /* Màu nền nửa trong suốt */
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            /* 15% từ trên và giữa theo chiều ngang */
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            /* Chiều rộng của modal */
            max-width: 600px;
            /* Chiều rộng tối đa */
            border-radius: 5px;
            /* Bo góc */
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* Điều chỉnh responsive */
        @media (max-width: 768px) {
            .action-button {
                margin: 5px
            }

            .message {
                display: none;
                /* Ẩn mặc định */
                padding: 15px;
                margin: 10px 0;
                border-radius: 5px;
            }
        }

        /* Thiết lập chung cho bảng */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }


        th,
        td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }


        th {
            background: #f8f9fa;
            text-align: center;
        }


        /* Responsive cho bảng dạng cột */
        @media (max-width: 768px) {

            table,
            thead,
            tbody,
            th,
            td,
            tr {
                display: block;
                /* Hiển thị từng ô bảng dưới dạng khối */
            }


            /* Ẩn tiêu đề của bảng */
            thead tr {
                display: none;
            }


            /* Thiết lập từng dòng dữ liệu thành một khối */
            tr {
                margin-bottom: 15px;
                border-bottom: 1px solid #ddd;
            }


            /* Hiển thị tiêu đề cột bên trong ô dữ liệu */
            td {
                position: relative;
                padding-left: 50%;
                text-align: left;
            }


            /* Gán tên tiêu đề cho mỗi ô dữ liệu bằng data-label */
            td::before {
                content: attr(data-label);
                /* Lấy giá trị từ data-label để làm tiêu đề */
                position: absolute;
                left: 10px;
                font-weight: bold;
                white-space: nowrap;
            }

        }
    </style>


</head>


<body>


    <!-- Header -->
    <?php include 'layouts/AdminHeader.php'; ?>

    

    <div class="container mt-5" style="display: flex; flex-direction: column; align-items: center; text-align: center;">
        <h1 style="margin-bottom: 30px;">Quản lý sản phẩm</h1>
        <!-- Form thêm sản phẩm -->
        <style>
            .form-container {
                display: flex;
                flex-wrap: wrap;
                /* Cho phép xuống dòng nếu không đủ chỗ */
                gap: 20px;
                /* Tăng khoảng cách giữa các khung (có thể điều chỉnh) */
            }


            input[type="text"],
            input[type="number"],
            input[type="file"],
            textarea,
            input[type="date"] {
                width: calc(100% - 20px);
                /* Đảm bảo các khung chiếm đầy chiều rộng, trừ đi padding */
                padding: 10px;
                /* Khoảng cách bên trong khung */
                box-sizing: border-box;
                /* Đảm bảo padding không làm tăng chiều rộng */
                border: 1px solid #ccc;
                /* Thêm đường viền để nhìn rõ hơn */
                border-radius: 4px;
                /* Bo góc khung */
            }


            textarea {
                height: 100px;
                /* Thiết lập chiều cao cho textarea */
                resize: vertical;
                /* Cho phép thay đổi kích thước theo chiều dọc */
                margin-top: 15px;
                /* Khoảng cách trên cho textarea */
            }


            .checkbox-group {
                display: flex;
                align-items: center;
                /* Căn giữa checkbox và label */
            }


            .checkbox-group input {
                margin-right: 5px;
                /* Khoảng cách giữa checkbox và label */
            }


            .btn {
                margin-top: 10px;
                /* Khoảng cách giữa nút và các khung input */
            }
        </style>
        <form action="" method="POST" enctype="multipart/form-data" class="mb-5 form-container">
            <input type="text" name="MaSP" placeholder="Mã sản phẩm" required>
            <input type="text" name="TenSP" placeholder="Tên sản phẩm" required>
            <input type="text" name="Maloai" placeholder="Mã loại" required>
            <input type="number" name="Giagoc" placeholder="Giá gốc" required>
            <input type="number" name="Giaban" placeholder="Giá bán" required>
            <input type="number" name="GiaKM" placeholder="Giá khuyến mãi">
            <input type="text" name="MaKM" placeholder="Mã khuyến mãi">


            <textarea name="Mota" placeholder="Mô tả sản phẩm"></textarea>


            <input type="text" name="TinhtrangTK" placeholder="Tình trạng">


            <div class="checkbox-group">
                <input type="checkbox" name="HotTrend" id="HotTrend">
                <label for="HotTrend">Hot Trend</label>
            </div>
            <div class="checkbox-group">
                <input type="checkbox" name="BestSeller" id="BestSeller">
                <label for="BestSeller">Best Seller</label>
            </div>


            <input type="text" name="IsClick" placeholder="Is Click">
            <input type="text" name="IsLike" placeholder="Is Like">

            <input type="date" name="Ngaycapnhat" required>


            <button type="submit" class="btn btn-primary" name="add_product">Thêm sản phẩm</button>
        </form>

        <!-- Hiển thị thông báo -->
        <?php if ($message): ?>
                <div class="message <?php echo strpos($message, 'Lỗi') === false ? 'success' : 'error'; ?>">
                    <?php echo $message; ?>
                </div>
        <?php endif; ?>


        <!-- Bảng sản phẩm -->
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>MaSP</th>
                        <th>TenSP</th>
                        <th>Giaban</th>
                        <th>Mota</th>
                        <th>HotTrend</th>
                        <th>BestSeller</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0) : ?>
                        <?php while ($row = $result->fetch_assoc()) : ?>
                            <tr>
                                <td data-label="MaSP"><?php echo $row['MaSP']; ?></td>
                                <td data-label="TenSP"><?php echo $row['TenSP']; ?></td>
                                <td data-label="Giaban"><?php echo $row['Giaban']; ?></td>
                                <td data-label="Mota"><?php echo $row['Mota']; ?></td>
                                <td data-label="HotTrend"><?php echo $row['HotTrend'] ? 'Có' : 'Không'; ?></td>
                                <td data-label="BestSeller"><?php echo $row['BestSeller'] ? 'Có' : 'Không'; ?></td>
                                <td data-label="Hành động"><!-- Sửa và Xóa -->
                                    <form action="QLsanpham.php" method="POST" class="d-inline">
                                        <input type="hidden" name="id" value="<?php echo $row['MaSP']; ?>">
                                        <button type="submit" name="delete_product" class="btn btn-danger">Xóa</button>
                                    </form>
                                    <button type="button" class="btn btn-warning action-button"
                                        onclick="openModal('<?php echo $row['MaSP']; ?>', '<?php echo $row['TenSP']; ?>',
                                    '<?php echo $row['Giaban']; ?>', '<?php echo $row['Mota']; ?>')">Sửa</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="7" class="text-center">Chưa có sản phẩm nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>


    </div>


    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <form method="post" action="">


                <input type="hidden" name="MaSP" id="MaSP">
                <input type="text" name="TenSP" id="TenSP" placeholder="Tên sản phẩm" required>
                <input type="number" name="Giaban" id="Giaban" placeholder="Giá bán" required>
                <textarea name="Mota" id="Mota" placeholder="Mô tả" required></textarea>


                <button type="submit" class="btn btn-primary" name="edit_product">Cập nhật</button>
            </form>
        </div>
    </div>


    <script>
        function openModal(MaSP, TenSP, Giaban, Mota) {
            document.getElementById('myModal').style.display = 'block';
            document.getElementById('MaSP').value = MaSP;
            document.getElementById('TenSP').value = TenSP;
            document.getElementById('Giaban').value = Giaban;
            document.getElementById('Mota').value = Mota;


        }


        function closeModal() {
            document.getElementById('myModal').style.display = "none"; // Ẩn modal
        }


        // Đóng modal khi nhấn ra ngoài
        window.onclick = function(event) {
            const modal = document.getElementById('myModal');
            if (event.target === modal) {
                closeModal();
            }
        }


        document.addEventListener('DOMContentLoaded', function() {
            const messageDiv = document.querySelector('.message');
            if (messageDiv) {
                setTimeout(() => {
                    messageDiv.style.display = 'none';
                }, 5000); // 5 giây
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
$conn->close(); // Close the database connection
?>