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

            <!-- Thanh tìm kiếm -->
            <?php
            if (isset($_GET['search-category'])) {
                // Lấy data tìm kiếm của người dùng trong đó: 
                // trim để xóa các khoảng trắng, strlower để chuyển về chữ thường, real_escape_string loại bỏ các kí tự đặc biệt
                $searchTerm = trim(strtolower($conn->real_escape_string($_GET['search-category'])));

                // Lấy các mã loại có têm trùng với data người dùng nhập vào
                $query = "SELECT MaLoai FROM loaisanpham WHERE TenLoai LIKE '%$searchTerm%'";
                $searchResult = mysqli_query($conn, $query);

                // kiểm tra kết quả tìm được
                if (mysqli_num_rows($searchResult) > 0) {
                    // Lấy kết quả tìm kiếm dưới dạng mảng
                    $searchData = mysqli_fetch_assoc($searchResult);
                    // Đưa đến trang category tại loại sản phẩm đó
                    header('Location: danhmucsp.php#' . $searchData['MaLoai']);
                    exit();
                } else {
                    // Đưa đến trang không tìm thấy loại sản phẩm
                    header('Location: danhmucsp.php?error=notfound');
                    exit();
                }
            }
            ?>
            <!-- form get  -->
            <form action="search_results.php" method="get" class="search-container py-3">
            <span><i class="fas fa-chevron-left search-icon"></i></span>
            <input type="text" class="form-control search-input" name="search" id="search-input" placeholder="Tìm kiếm bất kỳ">
            <!-- dropdown trong thanh tìm kiếm -->
            <div class="searchbar-dropdown">
                <ul class="searchbar-dropdown-menu" id="searchbar-dropdown-menu">
                    <?php foreach ($categoryData as $data): ?>
                        <li class="searchbar-dropdown-item">
                            <a href="<?php echo 'danhmucsp.php#' . $data['MaLoai']; ?>" style="color: black">
                                <?php echo $data['TenLoai'] ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <span><button type="submit" class="search-btn"><i class="fas fa-search"></i></button></span>
        </form>


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

                    <!-- Đăng nhập, đăng ký và đăng xuất -->
                    <?php if (isset($_SESSION['user']) && (isset($_SESSION['mySession']))): ?>
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