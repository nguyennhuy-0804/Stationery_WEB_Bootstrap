<?php
session_start(); // Bắt đầu phiên
include "database/conn.php"; // Kết nối đến cơ sở dữ liệu

// Tạo kết nối
$conn = new mysqli($server, $user, $pass, $database);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

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
        echo "Mã sản phẩm đã tồn tại. Vui lòng sử dụng mã khác.";
    } elseif ($result_name->num_rows > 0) {
        echo "Tên sản phẩm đã tồn tại. Vui lòng sử dụng tên khác.";
    } else {
        // Chỉ chạy câu lệnh SQL nếu các biến bắt buộc đã được xác định
        if ($MASP && $TenSP && $Giagoc && $Giaban && $Hinhanh && $Ngaycapnhat) {
            // Di chuyển tệp đã tải lên đến vị trí mong muốn
            $target_dir = "Homepage\\assets\\imgs\\products\\"; // Đường dẫn mới
            $target_file = $target_dir . basename($Hinhanh);
            $uploadOk = 1;

            // Kiểm tra xem tệp hình ảnh có phải là hình ảnh hay không
            $check = getimagesize($_FILES['Hinhanh']['tmp_name']);
            if($check === false) {
                echo "File không phải là hình ảnh.";
                $uploadOk = 0;
            }

            // Kiểm tra nếu tệp đã tồn tại
            if (file_exists($target_file)) {
                echo "Xin lỗi, tệp đã tồn tại.";
                $uploadOk = 0;
            }

            // Kiểm tra kích thước tệp (giới hạn 5MB)
            if ($_FILES['Hinhanh']['size'] > 5000000) {
                echo "Xin lỗi, tệp của bạn quá lớn.";
                $uploadOk = 0;
            }

            // Cho phép các định dạng tệp nhất định
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($imageFileType, $allowedTypes)) {
                echo "Xin lỗi, chỉ các tệp JPG, JPEG, PNG & GIF được phép.";
                $uploadOk = 0;
            }

            // Kiểm tra nếu $uploadOk đã được đặt về 0 do lỗi
            if ($uploadOk === 0) {
                echo "Xin lỗi, sản phẩm không được thêm vì lỗi trong việc tải lên tệp.";
            } else {
                // Thử di chuyển tệp đã tải lên
                if (move_uploaded_file($_FILES['Hinhanh']['tmp_name'], $target_file)) {
                    // Chèn vào cơ sở dữ liệu
                    $sql = "INSERT INTO sanpham (MaSP, TenSP, Maloai, Giagoc, Giaban, GiaKM, MaKM, Mota, TinhtrangTK, HotTrend, BestSeller, IsClick, IsLike, Hinhanh, Ngaycapnhat) 
                            VALUES ('$MASP', '$TenSP', '$MALOAI', '$Giagoc', '$Giaban', '$GiaKM', '$MaKM', '$Mota', '$TinhtrangTK', '$HotTrend', '$BestSeller', '$IsClick', '$IsLike', '$Hinhanh', '$Ngaycapnhat')";

                    if ($conn->query($sql) === TRUE) {
                        echo "Sản phẩm đã được thêm thành công!";
                    } else {
                        echo "Lỗi: " . $conn->error;
                    }
                } else {
                    echo "Lỗi khi tải lên hình ảnh.";
                }
            }
        } else {
            echo "Vui lòng điền tất cả các trường bắt buộc.";
        }
    }
}

// Sửa sản phẩm 
if (isset($_POST['edit_product'])) {
    $id = $_POST['id']; // Lấy ID sản phẩm

    // Lấy thông tin từ form
    $MASP = $_POST['MaSP'] ?? null;
    $MALOAI = $_POST['Maloai'] ?? null;
    $TenSP = $_POST['TenSP'] ?? null;
    $Giagoc = $_POST['Giagoc'] ?? null;
    $Giaban = $_POST['Giaban'] ?? null;
    $GiaKM = $_POST['GiaKM'] !== '' ? $_POST['GiaKM'] : null; // Nếu bỏ trống, đặt là null
    $MaKM = $_POST['MaKM'] !== '' ? $_POST['MaKM'] : null; // Nếu bỏ trống, đặt là null
    $Mota = $_POST['Mota'] ?? null;
    $TinhtrangTK = $_POST['TinhtrangTK'] ?? null;
    $HotTrend = isset($_POST['HotTrend']) ? 1 : 0; // Tùy chọn
    $BestSeller = isset($_POST['BestSeller']) ? 1 : 0; // Tùy chọn
    $IsClick = $_POST['IsClick'] ?? null;
    $IsLike = $_POST['IsLike'] ?? null;
    $Hinhanh = $_FILES['Hinhanh']['name'] ?? null; // Xử lý tải lên tệp
    $Ngaycapnhat = $_POST['Ngaycapnhat'] ?? null;

    // Khởi tạo mảng để chứa các trường cần cập nhật
    $update_fields = [];
    $params = [];

    // Kiểm tra từng trường và thêm vào mảng nếu có thông tin mới
    if ($TenSP !== null) {
        $update_fields[] = "TenSP=?";
        $params[] = $TenSP;
    }
    if ($MALOAI !== null) {
        $update_fields[] = "Maloai=?";
        $params[] = $MALOAI;
    }
    if ($Giagoc !== null) {
        $update_fields[] = "Giagoc=?";
        $params[] = $Giagoc;
    }
    if ($Giaban !== null) {
        $update_fields[] = "Giaban=?";
        $params[] = $Giaban;
    }
    if ($Mota !== null) {
        $update_fields[] = "Mota=?";
        $params[] = $Mota;
    }
    if ($TinhtrangTK !== null) {
        $update_fields[] = "TinhtrangTK=?";
        $params[] = $TinhtrangTK;
    }
    if ($HotTrend !== null) {
        $update_fields[] = "HotTrend=?";
        $params[] = $HotTrend;
    }
    if ($BestSeller !== null) {
        $update_fields[] = "BestSeller=?";
        $params[] = $BestSeller;
    }
    if ($IsClick !== null) {
        $update_fields[] = "IsClick=?";
        $params[] = $IsClick;
    }
    if ($IsLike !== null) {
        $update_fields[] = "IsLike=?";
        $params[] = $IsLike;
    }
    if ($Ngaycapnhat !== null) {
        $update_fields[] = "Ngaycapnhat=?";
        $params[] = $Ngaycapnhat;
    }

    // Xử lý GiaKM
    if ($GiaKM === null) {
        $update_fields[] = "GiaKM=NULL"; // Nếu xóa giá trị
    } else {
        $update_fields[] = "GiaKM=?";
        $params[] = $GiaKM;
    }

    // Xử lý MaKM
    if ($MaKM === null) {
        $update_fields[] = "MaKM=NULL"; // Nếu xóa mã khuyến mãi
    } else {
        $update_fields[] = "MaKM=?";
        $params[] = $MaKM;
    }

    // Nếu có hình ảnh mới được tải lên
    if ($Hinhanh) {
        $target_dir = "C:\\New xampp\\htdocs\\Homepage\\Homepage\\assets\\imgs\\products\\"; // Đường dẫn mới
        $target_file = $target_dir . basename($Hinhanh);
        
        // Thử di chuyển tệp đã tải lên
        if (move_uploaded_file($_FILES['Hinhanh']['tmp_name'], $target_file)) {
            $update_fields[] = "Hinhanh=?";
            $params[] = $Hinhanh; // Thêm tên tệp hình ảnh vào tham số
        } else {
            echo "Lỗi khi tải lên hình ảnh.";
            exit; // Dừng thực hiện nếu không tải được hình ảnh
        }
    }

    // Nếu có trường nào được thay đổi
    if (!empty($update_fields)) {
        $update_query = implode(", ", $update_fields);
        $sql = "UPDATE sanpham SET $update_query WHERE MaSP=?";
        
        // Thêm MASP vào cuối danh sách tham số
        $params[] = $MASP;

        // Tạo chuỗi định nghĩa kiểu cho bind_param
        $type_str = str_repeat('s', count($params) - 1); // Mặc định là 's' cho tất cả
        $type_str .= 's'; // Cuối cùng thêm 's' cho MASP

        // Thiết lập tham số cho bind_param
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($type_str, ...$params); // Sử dụng spread operator

        if ($stmt->execute()) {
            echo "Sản phẩm đã được cập nhật thành công!";
        } else {
            echo "Lỗi: " . $stmt->error; // In ra lỗi từ câu lệnh chuẩn bị
        }
    } else {
        echo "Không có thông tin nào để cập nhật.";
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
                    echo "Sản phẩm đã được xóa thành công từ tất cả các bảng!";
                } else {
                    echo "Lỗi khi xóa sản phẩm: " . $conn->error;
                }
            } else {
                echo "Lỗi khi xóa đánh giá: " . $conn->error;
            }
        } else {
            echo "Lỗi khi xóa chi tiết giỏ hàng: " . $conn->error;
        }
    } else {
        echo "Lỗi khi xóa chi tiết đơn hàng: " . $conn->error;
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
  /* Khi màn hình có kích thước nhỏ hơn 768px, chuyển bảng thành dạng cuộn ngang */
@media screen and (max-width: 768px) {
    /* Bao bọc bảng để cho phép cuộn ngang trên màn hình nhỏ */
    .table-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    /* Thiết lập bảng chiếm hết chiều rộng */
    table {
        width: 100%;
        border-collapse: collapse;
    }

    /* Ẩn tiêu đề bảng trên màn hình nhỏ */
    table thead {
        display: none; /* Ẩn tiêu đề bảng trên màn hình nhỏ */
    }

    /* Đảm bảo mỗi dòng của bảng chiếm toàn bộ chiều rộng */
    table, table tbody, table tr, table td {
        display: block;
        width: 100%;
    }

    /* Thêm khoảng cách dưới mỗi dòng trong bảng */
    table tr {
        margin-bottom: 10px;
    }

    /* Đảm bảo nội dung bảng hiển thị hợp lý, căn phải và thêm nhãn trước mỗi ô */
    table td {
        text-align: right;
        padding-left: 50%; /* Dành không gian cho nhãn */
        position: relative;
    }

    /* Thêm nhãn cho mỗi ô, hiển thị tên của trường dữ liệu */
    table td:before {
        content: attr(data-label); /* Hiển thị nhãn theo dữ liệu */
        position: absolute;
        left: 10px;
        font-weight: bold;
    }

    /* Cải thiện các nút hành động trên màn hình nhỏ, chúng chiếm toàn bộ chiều rộng */
    .action-button {
        width: 100%;
        margin-top: 5px;
    }

    /* Cải thiện modal trên màn hình nhỏ */
    .modal-dialog {
        max-width: 90%;
        margin: 30px auto;
    }

    .modal-content {
        width: 100%;
    }

    /* Khi textarea hiển thị trên màn hình nhỏ, cho phép cuộn dọc nếu cần */
    textarea {
        width: 100%;
        height: 150px; /* Chiều cao của textarea có thể thay đổi tuỳ theo yêu cầu */
        overflow-y: auto;
        resize: vertical; /* Cho phép người dùng thay đổi chiều cao của textarea */
    }
}

/* Khi màn hình có kích thước lớn hơn 768px, quay lại bố cục bảng chuẩn */
@media screen and (min-width: 769px) {
    .table-wrapper {
        overflow-x: initial;
    }

    /* Hiển thị lại tiêu đề bảng */
    table thead {
        display: table-header-group;
    }

    /* Loại bỏ nhãn trên mỗi ô trong bảng */
    table td:before {
        content: none;
    }

    /* Các nút hành động trở lại dạng ban đầu */
    .action-button {
        width: auto;
        margin-top: 0;
    }

    /* Không cho phép thay đổi kích thước textarea trên màn hình lớn */
    textarea {
        resize: none;
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
                flex-wrap: wrap; /* Cho phép xuống dòng nếu không đủ chỗ */
                gap: 20px; /* Tăng khoảng cách giữa các khung (có thể điều chỉnh) */
            }
            input[type="text"],
            input[type="number"],
            input[type="file"],
            textarea,
            input[type="date"] {
                width: calc(100% - 20px); /* Đảm bảo các khung chiếm đầy chiều rộng, trừ đi padding */
                padding: 10px; /* Khoảng cách bên trong khung */
                box-sizing: border-box; /* Đảm bảo padding không làm tăng chiều rộng */
                border: 1px solid #ccc; /* Thêm đường viền để nhìn rõ hơn */
                border-radius: 4px; /* Bo góc khung */
            }
            textarea {
                height: 100px; /* Thiết lập chiều cao cho textarea */
                resize: vertical; /* Cho phép thay đổi kích thước theo chiều dọc */
                margin-top: 15px; /* Khoảng cách trên cho textarea */
            }
            .checkbox-group {
                display: flex;
                align-items: center; /* Căn giữa checkbox và label */
            }
            .checkbox-group input {
                margin-right: 5px; /* Khoảng cách giữa checkbox và label */
            }
            .btn {
                margin-top: 10px; /* Khoảng cách giữa nút và các khung input */
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
            
            <input type="text" name="TinhtrangTK" placeholder="Tình trạng" required>
            
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
            <input type="file" name="Hinhanh" required>
            <input type="date" name="Ngaycapnhat" required>
            
            <button type="submit" class="btn btn-primary" name="add_product">Thêm sản phẩm</button>
        </form>


     <!-- Bảng sản phẩm -->
<div class="table-wrapper">
    <table class="table table-bordered">
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
                        <td data-label="Hành động">
                            <form action="" method="POST" class="d-inline">
                                <input type="hidden" name="id" value="<?php echo $row['MaSP']; ?>">
                                <button type="submit" name="delete_product" class="btn btn-danger">Xóa</button>
                            </form>
                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editModal<?php echo $row['MaSP']; ?>">Sửa</button>

                            <!-- Modal sửa sản phẩm -->
                            <div class="modal fade" id="editModal<?php echo $row['MaSP']; ?>" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Sửa sản phẩm</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="" method="POST" enctype="multipart/form-data">
                                                <input type="hidden" name="id" value="<?php echo $row['MaSP']; ?>">
                                                <input type="text" name="MaSP" value="<?php echo $row['MaSP']; ?>" required>
                                                <input type="text" name="TenSP" value="<?php echo $row['TenSP']; ?>" required>
                                                <input type="text" name="Maloai" value="<?php echo $row['Maloai']; ?>" required>
                                                <input type="number" name="Giagoc" value="<?php echo $row['Giagoc']; ?>" required>
                                                <input type="number" name="Giaban" value="<?php echo $row['Giaban']; ?>" required>
                                                <input type="number" name="GiaKM" value="<?php echo $row['GiaKM']; ?>"> 
                                                <input type="text" name="MaKM" value="<?php echo $row['MaKM']; ?>">
                                                <textarea name="Mota" required><?php echo $row['Mota']; ?></textarea>
                                                <input type="text" name="TinhtrangTK" value="<?php echo $row['TinhtrangTK']; ?>" required>
                                                <input type="checkbox" name="HotTrend" <?php echo $row['HotTrend'] ? 'checked' : ''; ?>> Hot Trend
                                                <input type="checkbox" name="BestSeller" <?php echo $row['BestSeller'] ? 'checked' : ''; ?>> Best Seller
                                                <input type="text" name="IsClick" value="<?php echo $row['IsClick']; ?>">
                                                <input type="text" name="IsLike" value="<?php echo $row['IsLike']; ?>">
                                                <input type="file" name="Hinhanh">
                                                <input type="date" name="Ngaycapnhat" value="<?php echo $row['Ngaycapnhat']; ?>" required>
                                                <button type="submit" class="btn btn-primary" name="edit_product">Cập nhật</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
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