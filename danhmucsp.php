<?php
session_start();
//Nếu chưa đăng nhập -> Chuyển tới trang Login
// if (!isset($_SESSION['mySession'])) {
//     header('location:login.php');
//     exit();
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Danh mục sản phẩm</title>

    <!-- lấy jQuery từ google apis hoặc dữ liệu người dùng -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <!-- Assets for star ratings -->
    <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-star-rating@4.1.2/js/star-rating.min.js"
        type="text/javascript"></script>
    <link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-star-rating@4.1.2/css/star-rating.min.css" media="all"
        rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-star-rating@4.1.2/themes/krajee-svg/theme.js"></script>
    <link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-star-rating@4.1.2/themes/krajee-svg/theme.css"
        media="all" rel="stylesheet" type="text/css" />

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

    <!-- CSS -->
    <link rel="stylesheet" href="css/danhmucsp.css">
</head>

<body>
    <?php include "database/conn.php"; ?>

    <!-- Header -->
    <?php include 'layouts/header.php'; ?>

    <section class="category-section">
        <div class="container-fluid text-center">
            <div class="row d-flex justify-content-center align-items-center">
                <div class="col-12 col-md-6" id="sach-col">
                    <div class="thumbnail big-thumbnail p-0">
                        <a href="danhmucsp.php#L1">
                            <!-- Mã loại-->
                            <img src="https://tiki.vn/blog/wp-content/uploads/2023/08/thumb-12.jpg" class="img-fluid"
                                alt="Sách" />
                            <div class="caption">
                                <h2>SÁCH</h2>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-6 col-md-3" id="phukien-but-col">
                    <div class="row">
                        <div class="thumbnail small-thumbnail small-thumbnail-first">
                            <a href="danhmucsp.php#L5">
                                <img src="https://shop.ueh.edu.vn/ueh-souvenir/wp-content/uploads/2022/05/sp-04-1.png"
                                    class="img-fluid" alt="Phụ kiện" />
                                <div class="caption">
                                    <h2>PHỤ KIỆN</h2>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="thumbnail small-thumbnail small-thumbnail-second">
                            <a href="danhmucsp.php#L3">
                                <img src="https://soklong.com/wp-content/uploads/2021/12/ce77ccea0aa6bdfecd4c355c3af3b9f5.jpg"
                                    class="img-fluid" alt="Bút" />
                                <div class="caption">
                                    <h2>BÚT</h2>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3" id="sotay-maytinh-col">
                    <div class="row">
                        <div class="thumbnail small-thumbnail small-thumbnail-third">
                            <a href="danhmucsp.php#L4">
                                <img src="https://inbaobigiay.vn/wp-content/uploads/2023/12/in-so-tay-6.jpg"
                                    class="img-fluid" alt="Sổ tay" />
                                <div class="caption">
                                    <h2>SỔ TAY</h2>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="thumbnail small-thumbnail small-thumbnail-fourth">
                            <a href="danhmucsp.php#L2">
                                <img src="https://vanphongphamhl.vn/upload_images/images/2024/01/29/may-tinh-casio-cho-hoc-sinh-cap-3-06.jpg"
                                    class="img-fluid" alt="Máy tính" />
                                <div class="caption">
                                    <h2>MÁY TÍNH</h2>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="danhmucsp-content" class="container-fluid">
        <div class="row justify-content-center my-4">
            <div class="col-12 col-md-6 col-lg-3">
                <select id="productFilter" class="form-select" onchange="filterProducts()">
                    <option value="" selected>Sắp xếp theo</option>
                    <option value="az">A - Z (Name)</option>
                    <option value="za">Z - A (Name)</option>
                    <option value="priceAsc">Giá tăng dần</option>
                    <option value="priceDesc">Giá giảm dần</option>
                </select>
            </div>
        </div>

        <?php
        // Sắp xếp sản phẩm
        $sortOption = isset($_GET['sort']) ? $_GET['sort'] : '';

        // Thay đổi thứ tự sắp xếp dựa trên tùy chọn đã chọn
        $sortQuery = '';
        switch ($sortOption) {
            case 'az':
                $sortQuery = "ORDER BY TenSP ASC"; // Sắp xếp theo tên từ A đến Z
                break;
            case 'za':
                $sortQuery = "ORDER BY TenSP DESC"; // Sắp xếp theo tên từ Z đến A
                break;
            case 'priceAsc':
                $sortQuery = "ORDER BY GiaKM IS NOT NULL DESC, GiaKM ASC, Giaban ASC"; // Sắp xếp theo giá từ thấp đến cao
                break;
            case 'priceDesc':
                $sortQuery = "ORDER BY GiaKM IS NOT NULL DESC, GiaKM DESC, Giaban DESC"; // Sắp xếp theo giá từ cao đến thấp
                break;
            default:
                $sortQuery = "ORDER BY MaSP ASC"; // Sắp xếp mặc định
                break;
        }

        // Lấy tất cả các danh mục sản phẩm
        $categoryResult = mysqli_query($conn, "SELECT * FROM loaisanpham ORDER BY Maloai ASC");
        // Duyệt qua từng danh mục sản phẩm
        while ($categoryRow = mysqli_fetch_array($categoryResult)) {
            // Lấy sản phẩm cho từng danh mục
            $categoryID = $categoryRow['Maloai']; // Lấy mã loại của danh mục hiện tại
            $productsResult = mysqli_query($conn, "SELECT * FROM sanpham WHERE Maloai = '$categoryID' $sortQuery LIMIT 3");
        ?>

            <div id="<?= $categoryID ?>" class="container-fluid"> <!-- id là mã loại sp để loop từng loại -->
                <h2 id="tenloaisp" class="text-uppercase fw-bold"><?= $categoryRow['TenLoai'] ?></h2> <!-- tên loại sp -->
                <div class="container text-center">
                    <div class="row align-items-center">
                        <?php
                        // Duyệt qua từng sản phẩm trong danh mục hiện tại
                        while ($productRow = mysqli_fetch_array($productsResult)) {
                            // Lấy mã sản phẩm
                            $productID = $productRow['MaSP'];

                            // lấy các rating của sản phẩm
                            $ratingResult = mysqli_query($conn, "SELECT * FROM danhgia WHERE MaSP = '$productID' ORDER BY MaDG ASC");

                            // lấy số lượng đánh giá của sản phẩm
                            $countRatingResult = mysqli_query($conn, "SELECT COUNT(DiemDG) AS totalRatings FROM danhgia WHERE MaSP = '$productID'");
                            $countRatingRow = mysqli_fetch_assoc($countRatingResult);
                            $totalRatings = $countRatingRow['totalRatings']; // Số lượng đánh giá

                            // Tính rating trung bình của sản phẩm
                            $avgRatingResult = mysqli_query($conn, "SELECT AVG(DiemDG) AS avgRating FROM danhgia WHERE MaSP = '$productID'");
                            $avgRatingRow = mysqli_fetch_assoc($avgRatingResult);
                            $averageRating = round($avgRatingRow['avgRating'], 0); // Làm tròn rating trung bình
                        ?>
                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                                <!-- Thông tin sản phẩm, mỗi div là một sản phẩm -->
                                <form action="giohang.php" method="post">
                                    <div class="card position-relative">
                                        <!-- Hiển thị nhãn giảm giá nếu sản phẩm có giảm giá (GiaKM) -->
                                        <?php if ($productRow['GiaKM'] != NULL) { ?>
                                            <div class="discount-badge">SALE</div>
                                        <?php } ?>
                                        <img src="./assets/imgs/products/<?= $productRow['Hinhanh'] ?>" class="card-img-top product-img img-fluid" alt="Hình ảnh <?= $productRow['TenSP'] ?>">
                                        <div class="card-body">
                                            <h5 class="card-title" data-bs-toggle="tooltip"
                                                data-bs-title="Đến trang Chi tiết sản phẩm <?= $productRow['TenSP'] ?>">
                                                <a href="./chitietsp.php?MaSP=<?= $productRow['MaSP'] ?>" class="text-decoration-none" style="color: #005f69;">
                                                    <?= $productRow['TenSP'] ?>
                                                </a>
                                            </h5>
                                            <p class="card-text">
                                                <?php if ($productRow['GiaKM'] != NULL) { ?>
                                                    <span class="text-decoration-line-through"><?= number_format($productRow['Giaban'], 0, ",", ".") ?><sup>đ</sup></span>
                                                <?php } ?>
                                                <span class="text-danger"><strong><?= number_format($productRow['GiaKM'] ? $productRow['GiaKM'] : $productRow['Giaban'], 0, ",", ".") ?><sup>đ</sup></strong></span>
                                            </p>

                                            <!-- Nút cho Thêm vào Giỏ hàng và Thêm vào Yêu thích -->
                                            <div class="btn-group" role="group">
                                                <button type="submit" class="btn btn-success" name="addtocartbtn" value="1" data-bs-toggle="tooltip"
                                                    data-bs-title="Thêm vào giỏ hàng"> <!--button thêm giỏ hàng -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                                                        <path
                                                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                                                    </svg>
                                                </button>
                                                <button type="button" onclick="location.href='DM yeu thich.php'" class="btn btn-danger" data-bs-toggle="tooltip"
                                                    data-bs-title="Thêm vào yêu thích"> <!--button thêm yêu thích -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd"
                                                            d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314" />
                                                    </svg>
                                                </button>
                                            </div>

                                            <!-- Progress Bar -->
                                            <div class="progress my-2" role="progressbar" style="height: 30px">
                                                <?php if ($productRow['TinhtrangTK'] != "Hết hàng") { ?>
                                                    <div class="progress-bar fw-bold" style="width: 80%">
                                                        <span>Còn hàng</span>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="progress-bar bg-danger fw-bold" style="width: 100%">
                                                        <span>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-fire" viewBox="0 0 16 16">
                                                                <path d="M8 16c3.314 0 6-2 6-5.5 0-1.5-.5-4-2.5-6 .25 1.5-1.25 2-1.25 2C11 4 9 .5 6 0c.357 2 .5 4-2 6-1.25 1-2 2.729-2 4.5C2 14 4.686 16 8 16m0-1c-1.657 0-3-1-3-2.75 0-.75.25-2 1.25-3C6.125 10 7 10.5 7 10.5c-.375-1.25.5-3.25 2-3.5-.179 1-.25 2 1 3 .625.5 1 1.364 1 2.25C11 14 9.657 15 8 15" />
                                                            </svg>Hết hàng
                                                        </span>
                                                    </div>
                                                <?php } ?>
                                            </div>

                                            <!-- Bootstrap Rating -->
                                            <div class="ratings d-flex flex-column align-items-center">
                                                <p class="mb-1"><?= $totalRatings ?> đánh giá</p>
                                                <input value="<?= $averageRating ?>" data-size="xs" class="rating" data-readonly="true" data-show-clear="false" data-show-caption="false">
                                            </div>

                                            <!-- Các trường ẩn để truyền dữ liệu sản phẩm -->
                                            <input type="hidden" name="hinhanh" value="<?= $productRow['Hinhanh'] ?>">
                                            <input type="hidden" name="tensp" value="<?= $productRow['TenSP'] ?>">
                                            <input type="hidden" name="giaban" value="<?= $productRow['Giaban'] ?>">
                                            <input type="hidden" name="giakm" value="<?= $productRow['GiaKM'] ?>">
                                            <input type="hidden" name="MaSP" value="<?= $productRow['MaSP'] ?>">
                                            <input type="hidden" name="soluong" value="1">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>
        <!-- Kết thúc lặp danh mục -->

        <!-- Header -->
        <?php include 'layouts/footer.php'; ?>
    </div>

    <!-- Scripts -->
    <script src="scripts/header.js"></script>
    <script src="scripts/homepage.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script>
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    </script>
    <script>
        function filterProducts() {
            var filterValue = document.getElementById('productFilter').value;
            window.location.href = "?sort=" + filterValue; // Chuyển hướng để áp dụng sắp xếp
        }

        // Hàm để lấy giá trị của một tham số truy vấn
        function getQueryParameter(param) {
            var urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(param);
        }

        // Đặt tùy chọn đã chọn khi tải trang
        window.onload = function() {
            var selectedSort = getQueryParameter('sort');
            if (selectedSort) {
                document.getElementById('productFilter').value = selectedSort;
            }
        }
    </script>
</body>

</html>