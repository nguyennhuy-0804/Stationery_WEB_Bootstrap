<?php
include "database/conn.php"; // Kết nối cơ sở dữ liệu
session_start();

//Nếu chưa đăng nhập -> Chuyển tới trang Login
// if (!isset($_SESSION['mySession'])) {
//     header('location:login.php');
//     exit();
// }

// Kiểm tra phương thức yêu cầu HTTP là POST và nút 'confirm' đã được gửi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm'])) {
    $email = $_POST['email']; // Lấy giá trị email từ form
    $new_password = $_POST['new_password']; // Lấy giá trị mật khẩu mới từ form
    $confirm_password = $_POST['confirm_password']; // Lấy giá trị xác nhận mật khẩu từ form

    // Kiểm tra xem email có tồn tại trong bảng 'thanhvien' không
    $sql = "SELECT MaTK FROM thanhvien WHERE Email = '$email'";
    $result = mysqli_query($conn, $sql); // Thực hiện truy vấn kiểm tra email

    // Nếu tìm thấy kết quả, tức là email tồn tại
    if (mysqli_num_rows($result) > 0) {
        // Lấy thông tin MaTK (Mã tài khoản) từ kết quả truy vấn
        $row = mysqli_fetch_assoc($result);
        $maTK = $row['MaTK']; // Lưu Mã tài khoản

        // Kiểm tra xem mật khẩu mới và mật khẩu xác nhận có trùng khớp không
        if ($new_password === $confirm_password) {
            // Mã hóa mật khẩu mới bằng hàm password_hash
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Cập nhật mật khẩu đã mã hóa vào bảng 'taikhoan'
            $update_sql = "UPDATE taikhoan SET MatKhau = '$hashed_password' WHERE MaTK = '$maTK'";

            // Nếu cập nhật mật khẩu thành công
            if (mysqli_query($conn, $update_sql)) {
                // Chuyển hướng tới trang login với thông báo thành công
                header("Location: login.php?reset=success");
                exit(); // Dừng script sau khi chuyển hướng
            } else {
                // Nếu có lỗi xảy ra trong quá trình cập nhật mật khẩu
                $error_message = "Có lỗi xảy ra trong quá trình cập nhật mật khẩu.";
            }
        } else {
            // Nếu mật khẩu mới và mật khẩu xác nhận không khớp
            $error_message = "Mật khẩu không khớp. Vui lòng thử lại.";
        }
    } else {
        // Nếu email không tồn tại trong hệ thống
        $error_message = "Email không tồn tại trong hệ thống.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu</title>

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

    <!-- JS -->
    <script src="carousel/vendors/jquery.min.js"></script>
    <script src="carousel/owlcarousel/owl.carousel.js"></script>

    <link rel="stylesheet" href="css/quenMK.css" />
</head>

<body>
    <!-- Header -->
    <?php include 'layouts/header.php'; ?>

    <div class="outlet">
        <div class="quenMK-container">
            <div class="title">
                <h1>ĐẶT LẠI MẬT KHẨU</h1>
            </div>

            <!-- Form để nhập email và mật khẩu mới -->
            <form action="quenMK.php" method="post" class="form-quenmk">
                <label for="email">Nhập lại địa chỉ email *</label>
                <input type="email" id="email" name="email" placeholder="Email" required> <!-- Ô nhập email -->

                <label for="new_password">Mật khẩu mới *</label>
                <input type="password" id="new_password" name="new_password" placeholder="Password" required> <!-- Ô nhập mật khẩu mới -->

                <label for="confirm_password">Nhập lại mật khẩu mới *</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Password" required> <!-- Ô nhập lại mật khẩu để xác nhận -->

                <!-- Hiển thị thông báo lỗi nếu có lỗi xảy ra -->
                <?php if (!empty($error_message)): ?>
                    <div class="error-message">
                        <?php echo $error_message; ?> <!-- Hiển thị lỗi từ biến $error_message -->
                    </div>
                <?php endif; ?>

                <!-- Nút xác nhận để gửi form -->
                <div class="buttons">
                    <button type="submit" class="confirm-btn" name="confirm">XÁC NHẬN</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'layouts/footer.php'; ?>

    <!-- Scripts -->
    <script src="scripts/header.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
</body>

</html>