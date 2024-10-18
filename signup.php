<?php
// Kết nối tới cơ sở dữ liệu
include "database/conn.php";
session_start();

//Nếu chưa đăng nhập -> Chuyển tới trang Login
// if (!isset($_SESSION['mySession'])) {
//     header('location:login.php');
//     exit();
// }

// Kiểm tra xem người dùng đã nhấn nút đăng ký hay chưa
if (isset($_POST['signup'])) {
    // Tạo mã ngẫu nhiên để tạo ID người dùng và ID tài khoản
    $str = rand(); // Tạo một số ngẫu nhiên
    $userID = "TV" . md5($str); // Tạo mã người dùng (MaTV) bằng cách mã hóa số ngẫu nhiên
    $accountID = "TK" . md5($str); // Tạo mã tài khoản (MaTK) bằng cách mã hóa số ngẫu nhiên

    // Lấy thông tin từ biểu mẫu đăng ký
    $fullname = $_POST['fullname']; // Họ và tên
    $email = $_POST['email']; // Địa chỉ email
    $username = $_POST['username']; // Tên đăng nhập
    $password = $_POST['password']; // Mật khẩu
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Mã hóa mật khẩu để bảo mật
    $address = $_POST['address']; // Địa chỉ
    $gender = $_POST['gender']; // Giới tính
    $phone = $_POST['phone']; // Số điện thoại
    $roleID = 1; // ID vai trò (có thể dùng để phân quyền, 1 là người dùng bình thường)
    $level = "Đồng"; // Cấp bậc thành viên

    // Thêm dữ liệu vào bảng taikhoan
    $sql1 = "INSERT INTO taikhoan (MaTK, TenDangNhap, MatKhau, RoleID)
    VALUES('$accountID', '$username', '$hashed_password', '$roleID');";

    // Thêm dữ liệu vào bảng thanhvien
    $sql2 = "INSERT INTO thanhvien (MaTV, MaTK, TenTV, Email, Diachi, Gioitinh, SDT, Hang)
    VALUES('$userID', '$accountID', '$fullname', '$email', '$address', '$gender', '$phone', '$level');";

    // Thực thi các truy vấn để thêm dữ liệu vào cơ sở dữ liệu
    mysqLi_query($conn, $sql1) && mysqLi_query($conn, $sql2);

    // Chuyển hướng đến trang đăng nhập sau khi đăng ký thành công
    header('location:login.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký tài khoản</title>

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

    <link rel="stylesheet" href="css/signup.css" />
</head>

<body>
    <!-- Header -->
    <?php include 'layouts/header.php'; ?>

    <div class="outlet">
        <div class="signup-container">
            <!-- Trang đăng ký -->
            <div class="title">
                <h1>Đăng ký tài khoản</h1>
            </div>

            <!-- Form đăng ký tài khoản, phương thức POST, gửi dữ liệu tới file signup.php -->
            <div class="form-container">
                <form action="signup.php" method="post" class="form-signup">
                    <!-- Nhập họ và tên người dùng -->
                    <label for="fullname">Họ và tên *</label>
                    <input type="text" id="fullname" name="fullname" placeholder="Họ và tên" required>

                    <!-- Nhập địa chỉ email -->
                    <label for="email">Địa chỉ email *</label>
                    <input type="email" id="email" name="email" placeholder="Email" required>

                    <!-- Nhập tên đăng nhập (username) -->
                    <label for="username">Username *</label>
                    <input type="text" id="username" name="username" placeholder="Username" required>

                    <!-- Nhập mật khẩu -->
                    <label for="password">Mật khẩu *</label>
                    <input type="password" id="password" name="password" placeholder="Password" required>

                    <!-- Nhập địa chỉ nhà -->
                    <label for="address">Địa chỉ nhà</label>
                    <input type="text" id="address" name="address" placeholder="Số nhà/tên đường/xã-phường/huyện/quận/thành phố">

                    <!-- Nhập số điện thoại -->
                    <label for="phone">Số điện thoại *</label>
                    <input type="tel" id="phone" name="phone" placeholder="Số điện thoại di động" required>

                    <!-- Phần lựa chọn giới tính -->
                    <div class="gender">
                        <label>Giới tính:</label>
                        <label for="male">
                            <!-- Checkbox Nam -->
                            <input type="radio" id="male" name="gender" value="Nam" required> Nam
                        </label>
                        <label for="female">
                            <!-- Checkbox Nữ -->
                            <input type="radio" id="female" name="gender" value="Nữ"> Nữ
                        </label>
                    </div>

                    <!-- Nút bấm đăng ký và đăng nhập -->
                    <div class="buttons">
                        <button type="submit" class="signup-btn" name="signup">Đăng ký</button> <!-- Nút bấm để gửi form đăng ký -->
                        <a href="login.php" class="login-btn">Đăng nhập</a> <!-- Liên kết đến trang đăng nhập -->
                    </div>
                </form>
            </div>
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