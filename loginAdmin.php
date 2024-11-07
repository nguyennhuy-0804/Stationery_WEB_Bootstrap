<?php
session_start();
include "database/conn.php";




if (isset($_POST['loginAdmin'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Lấy thông tin người dùng
    $sql = "SELECT taikhoan.MaTK, taikhoan.RoleID, taikhoan.MatKhau, admin.TenAD, admin.MaAD, admin.Email FROM
    taikhoan JOIN admin ON taikhoan.MaTK = admin.MaTK WHERE TenDangNhap = '$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hashedPassword = $row['MatKhau']; // Lấy mật khẩu đã được hash
            $roleId = $row['RoleID'];

            // Kiểm tra mật khẩu đã mã hóa (hash)
            if (password_verify($password, $hashedPassword)) {
                // Kiểm tra quyền truy cập
                if ($roleId == 0) { // Admin có quyền truy cập
                    $_SESSION['MaTK'] = $row['MaTK'];
                    $_SESSION['adminSession'] = $username;

                    // Lưu thông tin người dùng vào session
                    $_SESSION['admin'] = [
                        'TenAD' => $row['TenAD'],
                        'Email' => $row['Email'],
                        'MaAD' => $row['MaAD'],
                    ];

                    // Chuyển hướng đến trang chủ
                    header('location: QLthongke.php');
                    exit();
                } else { // Người dùng với RoleID = 1 không có quyền truy cập
                    $error_message = "Bạn không có quyền truy cập."; // Thông báo cho người dùng không phải admin
                }
            } else {
                // Nếu mật khẩu không phải hash, so sánh trực tiếp
                if ($password === $hashedPassword) {
                    if ($roleId == 0) { // Admin có quyền truy cập
                        $_SESSION['MaTK'] = $row['MaTK'];
                        $_SESSION['adminSession'] = $username;

                        // Lưu thông tin người dùng vào session
                        $_SESSION['admin'] = [
                            'TenAD' => $row['TenAD'],
                            'Email' => $row['Email'],
                            'MaAD' => $row['MaAD'],
                        ];

                        // Chuyển hướng đến trang chủ
                        header('location: QLthongke.php');
                        exit();
                    } else { // Người dùng với RoleID = 1 không có quyền truy cập
                        $error_message = "Bạn không có quyền truy cập."; // Thông báo cho người dùng không phải admin
                    }
                } else {
                    $error_message = "Mật khẩu không đúng."; // Thông báo mật khẩu sai
                }
            }
        } else {
            $error_message = "Tên đăng nhập không tồn tại."; // Thông báo tài khoản không tồn tại
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
            <h1>ĐĂNG NHẬP TÀI KHOẢN ADMIN</h1>
        </div>


        <!-- Form đăng nhập -->
        <form action="loginAdmin.php" method="post">
            <!--Tên tài khoản -->
            <label for="username">Tên tài khoản *</label>
            <input type="username" name="username" id="username" placeholder="Admin">




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
                <!-- Nút để đăng nhập và chuyển sang trang chủ admin -->
                <button type="submit" name="loginAdmin" class="login-btn">ĐĂNG NHẬP</button>
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