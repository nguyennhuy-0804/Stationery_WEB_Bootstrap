<?php
session_start();
include 'database/conn.php';

// Thông báo lỗi và thành công 
$error_message = "";
$success_message = "";

// Function thay đổi mật khẩu 
function changePassword($username, $newPassword)
{
    global $conn, $error_message, $success_message;

    // Kiểm tra username có tồn tại 
    $stmt = $conn->prepare("SELECT MatKhau FROM taikhoan WHERE TenDangNhap = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Hash mật khẩu mới 
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $updateStmt = $conn->prepare("UPDATE taikhoan SET MatKhau = ? WHERE TenDangNhap = ?");
        $updateStmt->bind_param("ss", $hashedPassword, $username);

        if ($updateStmt->execute()) {
            $success_message = "Đổi mật khẩu thành công!";
        } else {
            $error_message = "Đã xảy ra lỗi trong quá trình cập nhật mật khẩu.";
        }
        $updateStmt->close();
    } else {
        $error_message = "Người dùng không tồn tại!";
    }

    $stmt->close();
}

// Gửi form đi 
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['username']) && isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
        $username = $_POST['username'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        if ($newPassword !== $confirmPassword) {
            $error_message = "Mật khẩu mới và mật khẩu xác nhận không khớp!";
        } else {
            changePassword($username, $newPassword);
        }
    } else {
        $error_message = "Vui lòng điền đầy đủ thông tin!";
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
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <!-- Header -->
    <?php include 'layouts/header.php'; ?>

    <div class="outlet">
        <div class="quenMK-container">
            <div class="title">
                <h1>ĐẶT LẠI MẬT KHẨU</h1>
            </div>

            <!-- Form để nhập tên đăng nhập và mật khẩu mới -->
            <form action="quenMK.php" method="post" class="form-quenmk">
                <label for="username">Tên tài khoản *</label>
                <input type="username" name="username" id="username" placeholder="Username" required>

                <label for="new_password">Mật khẩu mới *</label>
                <input type="password" id="new_password" name="new_password" placeholder="Password" required>

                <label for="confirm_password">Nhập lại mật khẩu mới *</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Password" required>

                <!-- Hiển thị thông báo lỗi nếu có lỗi xảy ra -->
                <?php if (!empty($error_message)): ?>
                    <div class="error-message">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <!-- Hiển thị thông báo thành công nếu có -->
                <?php if (!empty($success_message)): ?>
                    <div class="success-message" style="color: green;">
                        <?php echo $success_message; ?>
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