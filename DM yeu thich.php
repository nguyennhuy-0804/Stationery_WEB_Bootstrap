<?php
include "database/conn.php"; // Kết nối đến cơ sở dữ liệu
session_start();

// Nếu chưa đăng nhập -> Chuyển tới trang Login
// if (!isset($_SESSION['mySession'])) {
//     header('location:login.php'); // Chuyển hướng đến trang đăng nhập
//     exit();
// }

//* Nếu chưa đăng nhập -> Chuyển tới trang Login
if (!isset($_SESSION['mySession'])) {
    header('location:login.php');
    exit();
}

// User hiện tại
$userID = $_SESSION['user']['MaTV']; // Lấy mã thành viên của người dùng hiện tại
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh mục yêu thích</title>

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

    <link rel="stylesheet" href="css/DM yeu thich.css" />
</head>

<body>
    <!-- Header -->
    <?php include 'layouts/header.php'; ?>

    <div class="container">
        <!-- Phần thông tin người dùng -->
        <div class="profile-section">
            <h2>THÔNG TIN CÁ NHÂN</h2>
            <div class="profile-info">
                <img src="https://static.vecteezy.com/system/resources/thumbnails/002/387/693/small_2x/user-profile-icon-free-vector.jpg" alt="Profile Picture"> <!-- Hình đại diện người dùng -->
                <div>
                    <!-- Hiển thị thông tin người dùng -->
                    <?php if (isset($_SESSION['user'])): ?>
                        <p><strong>Họ và tên:</strong> <?= $_SESSION['user']['TenTV'] ?? 'Không có dữ liệu' ?></p> <!-- Tên người dùng -->
                        <p><strong>Email:</strong> <?= $_SESSION['user']['Email'] ?? 'Không có dữ liệu' ?></p> <!-- Địa chỉ email -->
                        <p><strong>Username:</strong> <?= $_SESSION['user']['TenDangNhap'] ?? 'Không có dữ liệu' ?></p> <!-- Tên đăng nhập -->
                        <p><strong>Địa chỉ:</strong> <?= $_SESSION['user']['Diachi'] ?? 'Không có dữ liệu' ?></p> <!-- Địa chỉ người dùng -->
                        <p><strong>Số điện thoại:</strong> <?= $_SESSION['user']['SDT'] ?? 'Không có dữ liệu' ?></p> <!-- Số điện thoại -->
                        <p><strong>Hạng TV:</strong> <?= $_SESSION['user']['Hang'] ?? 'Không có dữ liệu' ?></p> <!-- Hạng thành viên -->
                    <?php else: ?>
                        <p>Bạn chưa đăng nhập.</p> <!-- Thông báo nếu người dùng chưa đăng nhập -->
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <hr>


    <!-- Phần yêu thích -->
    <div class="cart-section">
        <div class="d-flex align-items-center">
            <i class="fa-solid fa-cart-shopping"></i> <!-- Icon giỏ hàng -->
            <h2>DANH SÁCH YÊU THÍCH</h2>
        </div>
        <br>
        <div class="cart-item">
            <img src="./assets/imgs/products/SP1.jpg" alt="SP1"> <!-- Hình ảnh sản phẩm -->
            <div class="cart-item-details">
                <p><strong>Sách Kinh tế vi mô</strong></p> <!-- Tên sản phẩm -->
                <p>Mã SP: SP1</p> <!-- Mã sản phẩm -->
                <p>Chương trình khuyến mãi:
                    <span style="color:  #ff4b4b; font-weight: bold">Flash Sale - Giảm giá</span>
                </p>


            </div>
            <div class="cart-item-price">
                <h5 class="text-secondary text-decoration-line-through">60000<sup>đ</sup></h5> <!-- Giá gốc -->
                <h4 class="text-danger">
                    50000<sup>đ</sup> <!-- Giá hiện tại (có thể là giá khuyến mãi hoặc giá gốc) -->
                </h4>
            </div>


        </div>
        <div class="cart-item">
            <img src="./assets/imgs/products/SP2.jpg" alt="SP2"> <!-- Hình ảnh sản phẩm -->
            <div class="cart-item-details">
                <p><strong>Sách Kinh tế vĩ mô</strong></p> <!-- Tên sản phẩm -->
                <p>Mã SP: SP2</p> <!-- Mã sản phẩm -->
                <p>Chương trình khuyến mãi:
                    <span style="color:  #ff4b4b; font-weight: bold">Flash Sale - Giảm giá</span>
                </p>


            </div>
            <div class="cart-item-price">
                <h5 class="text-secondary text-decoration-line-through">60000<sup>đ</sup></h5> <!-- Giá gốc -->
                <h4 class="text-danger">
                    50000<sup>đ</sup> <!-- Giá hiện tại (có thể là giá khuyến mãi hoặc giá gốc) -->
                </h4>
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