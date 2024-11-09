<?php
session_start(); // Bắt đầu phiên
include "database/conn.php"; // Kết nối đến cơ sở dữ liệu
$message = "";
// Xử lý thêm khuyến mãi
if (isset($_POST['add_discount'])) {
    $MaKM = $_POST['MaKM'] ?? null;
    $TenCT = $_POST['TenCT'] ?? null;
    $PhamtramKM = $_POST['PhamtramKM'] ?? null; // Kiểu dữ liệu số
    $DieuKien = $_POST['DieuKien'] ?? null;
    $NgayBD = $_POST['NgayBD'] ?? null;
    $NgayKT = $_POST['NgayKT'] ?? null;


    if ($MaKM && $TenCT && $PhamtramKM !== null && $DieuKien && $NgayBD && $NgayKT) {
        // Kiểm tra xem MaKM và TenCT có bị trùng không
        $checkSql = "SELECT * FROM khuyenmai WHERE MaKM = ? OR TenCT = ?";
        $stmt = $conn->prepare($checkSql);
        $stmt->bind_param("ss", $MaKM, $TenCT);
        $stmt->execute();
        $resultCheck = $stmt->get_result();


        if ($resultCheck->num_rows > 0) {
            $message = "Trùng lặp: Mã khuyến mãi hoặc tên chi tiết đã tồn tại. Vui lòng nhập khác.";
        } else {
            $sql = "INSERT INTO khuyenmai (MaKM, TenCT, PhamtramKM, DieuKien, NgayBD, NgayKT)
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmtInsert = $conn->prepare($sql);
            // Chỉnh sửa bind_param cho đúng kiểu dữ liệu
            $stmtInsert->bind_param("ssisss", $MaKM, $TenCT, $PhamtramKM, $DieuKien, $NgayBD, $NgayKT);


            if ($stmtInsert->execute()) {
                $message = "Khuyến mãi đã được thêm thành công!";
            } else {
                $message = "Lỗi: " . $stmtInsert->error;
            }
        }
    } else {
        $message = "Vui lòng điền tất cả các trường bắt buộc.";
    }
}


// Sửa khuyến mãi
if (isset($_POST['edit_discount'])) {
    $MaKM = $_POST['MaKM'] ?? null;
    $TenCT = $_POST['TenCT'] ?? null;
    $PhamtramKM = $_POST['PhamtramKM'] ?? null; // Kiểu dữ liệu số
    $DieuKien = $_POST['DieuKien'] ?? null;
    $NgayBD = $_POST['NgayBD'] ?? null;
    $NgayKT = $_POST['NgayKT'] ?? null;


    if ($MaKM) {
        $sql = "UPDATE khuyenmai SET TenCT=?, PhamtramKM=?, DieuKien=?, NgayBD=?, NgayKT=? WHERE MaKM=?";
        $stmt = $conn->prepare($sql);
        // Chỉnh sửa bind_param cho đúng kiểu dữ liệu
        $stmt->bind_param("sissss", $TenCT, $PhamtramKM, $DieuKien, $NgayBD, $NgayKT, $MaKM);
       
        if ($stmt->execute()) {
            $message = "Khuyến mãi đã được cập nhật thành công!";
        } else {
            $message = "Lỗi: " . $stmt->error;
        }
    } else {
        $message = "Mã khuyến mãi không hợp lệ.";
    }
}


// Xử lý xóa khuyến mãi
if (isset($_POST['delete_discount'])) {
    $MaKM = $_POST['MaKM'] ?? null;


    if ($MaKM) {
        // Cập nhật bảng donhang và sanpham trước khi xóa khuyến mãi
        $updateDonHangSql = "UPDATE donhang SET MaKM = NULL WHERE MaKM = ?";
        $stmtUpdateDonHang = $conn->prepare($updateDonHangSql);
        $stmtUpdateDonHang->bind_param("s", $MaKM);
        $stmtUpdateDonHang->execute();


        $updateSanPhamSql = "UPDATE sanpham SET MaKM = NULL WHERE MaKM = ?";
        $stmtUpdateSanPham = $conn->prepare($updateSanPhamSql);
        $stmtUpdateSanPham->bind_param("s", $MaKM);
        $stmtUpdateSanPham->execute();


        // Sau khi cập nhật, tiến hành xóa khuyến mãi
        $sql = "DELETE FROM khuyenmai WHERE MaKM=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $MaKM);
       
        if ($stmt->execute()) {
            $message = "Khuyến mãi đã được xóa thành công!";
        } else {
            $message = "Lỗi: " . $stmt->error;
        }
    } else {
        $message = "Mã khuyến mãi không hợp lệ.";
    }
}


// Lấy danh sách khuyến mãi
$sql = "SELECT * FROM khuyenmai";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="vi">
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
    <link rel="stylesheet" href="css/QLKM.css" />
    <link rel="stylesheet" href="css/layouts/header.css" />
    <link rel="stylesheet" href="css/layouts/footer.css" />
    <!-- Header -->
   
</head>
<body>
   
    <?php include 'layouts/AdminHeader.php'; ?>
    <h1>Quản lý Khuyến Mãi</h1>


    <!-- Form thêm khuyến mãi -->
    <form id="add-discount-form" method="post" action="">
        <input type="text" name="MaKM" placeholder="Mã Khuyến Mãi" required>
        <input type="text" name="TenCT" placeholder="Tên Chi Tiết" required>
        <input type="number" name="PhamtramKM" placeholder="Phần trăm Khuyến Mãi" required>
        <input type="text" name="DieuKien" placeholder="Điều Kiện" required>
        <input type="date" name="NgayBD" placeholder="Ngày Bắt Đầu" required>
        <input type="date" name="NgayKT" placeholder="Ngày Kết Thúc" required>
        <button class="action-button" type="submit" name="add_discount">Thêm Khuyến Mãi</button>
    </form>

    <!-- Hiển thị thông báo -->
    <?php if ($message): ?>
        <div class="message <?php echo strpos($message, 'Lỗi') === false ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>


       <!-- Bảng danh sách khuyến mãi -->
       <table>
        <thead>
            <tr>
                <th>Mã Khuyến Mãi</th>
                <th>Tên Chi Tiết</th>
                <th>% Khuyến Mãi</th>
                <th>Điều Kiện</th>
                <th>Ngày Bắt Đầu</th>
                <th>Ngày Kết Thúc</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td data-label="Mã Khuyến Mãi"><?php echo $row['MaKM']; ?></td>
                <td data-label="Tên Chi Tiết"><?php echo $row['TenCT']; ?></td>
                <td data-label="% Khuyến Mãi"><?php echo $row['PhamtramKM']; ?></td>
                <td data-label="Điều Kiện"><?php echo $row['DieuKien']; ?></td>
                <td data-label="Ngày Bắt Đầu"><?php echo $row['NgayBD']; ?></td>
                <td data-label="Ngày Kết Thúc"><?php echo $row['NgayKT']; ?></td>
                <td data-label="Hành Động">
                    <button class="action-button" onclick="openModal('<?php echo $row['MaKM']; ?>', '<?php echo $row['TenCT']; ?>', '<?php echo $row['PhamtramKM']; ?>', '<?php echo $row['DieuKien']; ?>', '<?php echo $row['NgayBD']; ?>', '<?php echo $row['NgayKT']; ?>')">Sửa</button>


                    <form method="post" action="" style="display:inline;">
                        <input type="hidden" name="MaKM" value="<?php echo $row['MaKM']; ?>">
                        <button class="action-button" type="submit" name="delete_discount" onclick="return confirm('Bạn có chắc chắn muốn xóa khuyến mãi này?');">Xóa</button>
                    </form>
                </td>
           
            </tr>
           
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="7">Không có khuyến mãi nào.</td>
        </tr>
    <?php endif; ?>
</tbody>
    </table>
   


    <!-- Modal sửa khuyến mãi -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <form method="post" action="">
                <input type="hidden" name="MaKM" id="MaKM">
                <input type="text" name="TenCT" id="TenCT" placeholder="Tên Chi Tiết" required>
                <input type="number" name="PhamtramKM" id="PhamtramKM" placeholder="Phần trăm Khuyến Mãi" required>
                <input type="text" name="DieuKien" id="DieuKien" placeholder="Điều Kiện" required>
                <input type="date" name="NgayBD" id="NgayBD" placeholder="Ngày Bắt Đầu" required>
                <input type="date" name="NgayKT" id="NgayKT" placeholder="Ngày Kết Thúc" required>
                <button type="submit" name="edit_discount" class="edit_discount">Cập Nhật Khuyến Mãi</button>
            </form>
        </div>
    </div>


    <script>
        function openModal(MaKM, TenCT, PhamtramKM, DieuKien, NgayBD, NgayKT) {
            document.getElementById('MaKM').value = MaKM;
            document.getElementById('TenCT').value = TenCT;
            document.getElementById('PhamtramKM').value = PhamtramKM;
            document.getElementById('DieuKien').value = DieuKien;
            document.getElementById('NgayBD').value = NgayBD;
            document.getElementById('NgayKT').value = NgayKT;
            document.getElementById('myModal').style.display = "block"; // Hiện modal
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
$conn->close();
?>

