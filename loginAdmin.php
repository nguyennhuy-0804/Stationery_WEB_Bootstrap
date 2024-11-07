<?php
session_start();
include "database/conn.php";


// Thông tin kết nối cơ sở dữ liệu
$server = "localhost";   // Địa chỉ máy chủ cơ sở dữ liệu
$user = "root";          // Tên người dùng (username)
$pass = "";              // Mật khẩu người dùng
$database = "uehstationery"; // Tên cơ sở dữ liệu


// Tạo kết nối
$conn = new mysqli($server, $user, $pass, $database);


// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối cơ sở dữ liệu thất bại: " . $conn->connect_error);
}


// Khởi tạo thông báo lỗi
$error_message = "";


// Xử lý đăng nhập
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];


    // Truy vấn kiểm tra tài khoản
    $sql = "SELECT MaTK, RoleID, MatKhau FROM taikhoan WHERE TenDangNhap = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();


    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row['MatKhau']; // Lấy mật khẩu đã được hash
        $roleId = $row['RoleID'];


        // Kiểm tra mật khẩu đã mã hóa (hash)
        if (password_verify($password, $hashedPassword)) {
            // Kiểm tra quyền truy cập
            if ($roleId == 0) { // Admin có quyền truy cập
                $_SESSION['MaTK'] = $row['MaTK'];
                $_SESSION['RoleID'] = $roleId;
                header("Location: QLthongke.php");
                exit();
            } else { // Người dùng với RoleID = 1 không có quyền truy cập
                $error_message = "Bạn không có quyền truy cập."; // Thông báo cho người dùng không phải admin
            }
        } else {
            // Nếu mật khẩu không phải hash, so sánh trực tiếp
            if ($password === $hashedPassword) {
                if ($roleId == 0) { // Admin có quyền truy cập
                    $_SESSION['MaTK'] = $row['MaTK'];
                    $_SESSION['RoleID'] = $roleId;
                    header("Location: QLthongke.php");
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
                <button type="submit" name="login" class="login-btn">ĐĂNG NHẬP</button>
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



