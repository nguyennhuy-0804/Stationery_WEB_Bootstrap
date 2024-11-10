<?php

// // Kiểm tra xem user đăng nhập chưa
// if (isset($_SESSION['mySession'])) {
//     header('location: homepage.php');
//     exit();
// }



if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];

    // Kết nối đến cơ sở dữ liệu
    $mysqli = new mysqli("localhost", "username", "password", "database");

    // Kiểm tra kết nối
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Truy vấn tìm kiếm (cần sử dụng prepared statements để bảo mật hơn)
    $stmt = $mysqli->prepare("SELECT * FROM products WHERE name LIKE ? OR category LIKE ?");
    $searchTerm = "%" . $searchQuery . "%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    // Hiển thị kết quả
    while ($row = $result->fetch_assoc()) {
        echo "<div>" . $row['name'] . " - " . $row['category'] . "</div>";
    }

    // Đóng kết nối
    $stmt->close();
    $mysqli->close();
}

// Lấy loại sản phẩm
$categoryResult = mysqli_query($conn, 'SELECT MaLoai, TenLoai FROM loaisanpham');
// Lấy các hàng dưới dạng mảng kết hợp
$categoryData = mysqli_fetch_all($categoryResult, MYSQLI_ASSOC);
?>

<body>
    <nav class="navbar navbar-expand-lg bg-header-top">
        <div class="container-fluid align-items-center">

            <!-- Logo và title của web-->
            <div class="logo-container">
                <div class="text-center text-lg-left py-2">
                    <img src="assets/svgs/logo.svg" alt="UEH Logo" class="navbar-brand-logo">
                </div>
                <div class="text-center text-lg-left py-2 px-2">
                    <a href="QLthongke.php">
                        <p class="navbar-brand-text">STATIONERY</p>
                    </a>
                </div>
            </div>

          
            <!-- Các icon -->
            <div class="toolbar-container">
                <div class="navbar-toolbar py-1">
                    <div class="icon-container">
                        <i class="fas fa-phone-alt"></i>
                        <i class="fas fa-map-marker-alt"></i>
                        <i class="fab fa-facebook-f"></i>
                    </div>
                </div>
            </div>

            <div class="language-container">
                <div class="navbar-toolbar py-1">
                    <div class="locale-switcher" id="languageSwitch" onclick="toggleLanguage()">
                        <span id="languageText" class="active-language me-2">VN</span>
                        <i class="fas fa-globe"></i>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Các button chuyển trang -->
    <nav class="navbar navbar-expand-lg bg-header-bottom">
        <div class="container-fluid justify-content-end align-items-center">
            <!-- button khi ở màn hình điện thoại -->
            <button class="navbar-toggler bg-light my-1" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="true"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Các link của các trang -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 mx-auto">
                    <!-- trang QL Thống kê -->
                    <li class="nav-item">
                        <a class="nav-link" href="QLthongke.php">
                            <i class="bi bi-people-fill navbar-header-logo"></i>
                            QL Thống kê
                        </a>
                    </li>

                    <li>
                        <div class="vertical-divider"></div>
                    </li>
                    <!-- QL sản phẩm -->
                    <li class="nav-item">
                        <a class="nav-link" href="QLsanpham.php">
                            <i class="bi bi-people-fill navbar-header-logo"></i>
                            QL Sản phẩm
                        </a>
                    </li>

                    <li>
                        <div class="vertical-divider"></div>
                    </li>
                    <!-- trang QL Khuyến mãi -->
                    <li class="nav-item">
                        <a class="nav-link" href="QLkhuyenmai.php">
                            <i class="bi bi-fire navbar-header-logo"></i>
                            QL Khuyến mãi
                        </a>
                    </li>

                    <li>
                        <div class="vertical-divider"></div>
                    </li>

                    <!-- trang QL Khuyến mãi -->
                    <li class="nav-item">
                        <a class="nav-link" href="QLuser.php">
                            <i class="bi bi-fire navbar-header-logo"></i>
                            QL người dùng
                        </a>
                    </li>

                    <li>
                        <div class="vertical-divider"></div>
                    </li>

                    <!-- Đăng nhập, đăng ký và đăng xuất -->
                    <?php if (isset($_SESSION['admin']) && (isset($_SESSION['adminSession']))): ?>
                        <li class="nav-item dropdown">
                            <!-- In ra tên của user -->
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="bi bi-person-circle navbar-header-logo"></i>
                                <?= $_SESSION['admin']['TenAD'] ?? 'Không có dữ liệu' ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href='logout.php' name="logout" class="logout-btn">Đăng xuất</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="bi bi-box-arrow-in-right navbar-header-logo"></i>
                                Đăng nhập
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="login.php">Đăng xuất</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>



</body>

</html>