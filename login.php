<?php
session_start();
include 'database/conn.php';

// Kiểm tra xem user đăng nhập chưa
if (isset($_SESSION['mySession'])) {
    header('location: index.php');
    exit();
}

// Đăng nhập tài khoản
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Lấy thông tin người dùng
    $sql = "SELECT taikhoan.MaTK, taikhoan.TenDangNhap, taikhoan.MatKhau, thanhvien.MaTV, thanhvien.TenTV, thanhvien.Email, thanhvien.Gioitinh, thanhvien.DiaChi, thanhvien.SDT, thanhvien.Hang
            FROM taikhoan
            JOIN thanhvien ON taikhoan.MaTK = thanhvien.MaTK
            WHERE taikhoan.TenDangNhap = '$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        // Kiểm tra mật khẩu
        if (password_verify($password, $row['MatKhau']) || $password == $row['MatKhau']) {
            // Lưu trữ MaTK và thông tin người dùng vào session
            $_SESSION['MaTK'] = $row['MaTK'];
            $_SESSION['mySession'] = $username;

            // Lưu thông tin người dùng vào session
            $_SESSION['user'] = [
                'TenTV' => $row['TenTV'],
                'Email' => $row['Email'],
                'Gioitinh' => $row['Gioitinh'],
                'Diachi' => $row['DiaChi'],
                'SDT' => $row['SDT'],
                'MaTK' => $row['MaTK'],
                'MaTV' => $row['MaTV'],
                'TenDangNhap' => $row['TenDangNhap'],
                'Hang' => $row['Hang'],
            ];

            // Chuyển hướng đến trang chủ
            header('location: index.php');
            exit();
        } else {
            $error_message = 'Tài khoản hoặc mật khẩu sai';
        }
    } else {
        $error_message = 'Tài khoản hoặc mật khẩu sai';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UEH Stationery</title>

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

    <!-- Custom Styles -->
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <!-- Header -->
    <?php include 'layouts/header.php'; ?>

    <div class="container login-container">
        <div class="title">
            <h1>ĐĂNG NHẬP TÀI KHOẢN</h1>
        </div>

        <!-- Form đăng nhập -->
        <form action="login.php" method="post">
            <!--Tên tài khoản -->
            <label for="username">Tên tài khoản *</label>
            <input type="username" name="username" id="username" placeholder="Username">


            <!-- Mật khẩu -->
            <label for="password">Mật khẩu *</label>
            <input type="password" name="password" id="password" placeholder="Password">


            <!-- Quên mật khẩu -->
            <div class="extra-options">
                <a href="quenMK.php">Quên mật khẩu?</a>
            </div>

            <!-- Hiển thị thông báo lỗi (nếu thông tin đăng nhập sai) -->
            <?php if (!empty($error_message)): ?>
                <div class="error-message" style="color: red; margin-top: 10px; font-weight: bold;">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <div class="buttons">
                <!-- Nút để đăng nhập và chuyển sang trang đăng ký -->
                <button type="submit" name="login" class="login-btn">ĐĂNG NHẬP</button>
                <!-- Nút để chuyển đến trang đăng ký -->
                <button type="button" onclick="window.location.href='signup.php'" class="signup-btn">ĐĂNG KÝ</button>
            </div>
        </form>
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