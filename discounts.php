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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UEH Stationery</title>

    <!-- get jQuery from the google apis or use your own -->
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
    <link rel="stylesheet" href="carousel/owlcarousel/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="carousel/owlcarousel/assets/owl.theme.default.min.css">

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

    <link rel="stylesheet" href="css/discount.css" />
</head>

<body>
    <!-- Kết nối database -->
    <?php include 'database/conn.php'; ?>

    <!-- Header -->
    <?php include 'layouts/header.php'; ?>

    <div class="outlet">
        <!-- Banner Section -->
        <section class="banner-section">
            <div class="container-fluid bg-banner">
                <div class="row">
                    <div class="col-md-7 col-sm-12">
                        <div id="carouselHotControls" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="assets/imgs/banners/bn_1.png" alt="" class="big-banner">
                                </div>
                                <div class="carousel-item">
                                    <img src="assets/imgs/banners/bn_2.png" alt="" class="big-banner">
                                </div>
                                <div class="carousel-item">
                                    <img src="assets/imgs/banners/bn_4.png" alt="" class="big-banner">
                                </div>
                            </div>

                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselHotControls"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>

                            <button class="carousel-control-next" type="button" data-bs-target="#carouselHotControls"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>

                    <div class="col-md-5 col-sm-12">
                        <div class="col">
                            <div class="row-6">
                                <img src="assets/imgs/banners/bn_4.png" alt="" class="small-banner">
                            </div>
                            <div class="row-6">
                                <img src="assets/imgs/banners/bn_2.png" alt="" class="small-banner">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Flashsales Section -->
        <section class="flashsale-section">
            <?php
            // Lấy tất cả sản phẩm khuyến mãi từ database
            $flashsaleResult = mysqli_query($conn, 'SELECT * FROM sanpham INNER JOIN khuyenmai ON khuyenmai.MaKM = sanpham.MaKM WHERE sanpham.MaKM IS NOT NULL');
            $flashsaleData = mysqli_fetch_all($flashsaleResult, MYSQLI_ASSOC);
            ?>
            <!-- Thông tin các sản phẩm khuyến mãi và được đặt trong carousel-->
            <div class="flashsale-carousel owl-carousel owl-theme">
                <!-- Mỗi sản phẩm  là một thẻ div trong foreach-->
                <?php foreach ($flashsaleData as $data): ?>
                    <div class="container-fluid bg-flashsale" data-endtime="<?php echo $data['NgayKT']; ?>">
                        <!-- Để thành form -->
                        <div class="row">
                            <!-- Thông tin sản phẩm khuyến mãi -->
                            <div class="col-md-6 p-0">
                                <div
                                    class="d-flex justify-content-center align-items-center mh-100 h-100 position-relative">
                                    <!-- Countdown cảu tiên để flashsale -->
                                    <div class="bg-flash-countdown position-absolute top-0 start-0 ">
                                        <img src="assets/imgs/buttons/btn_flashsale.png" alt=""
                                            style="height: 70px; width: 50px"></img>
                                        <p class="bg-flash-countdown-text">FLASH SALE: <span
                                                id="flashsale-countdown"></span></p>
                                    </div>
                                    <!-- card thông tin của sản phẩm -->
                                    <div class="bg-flash-info  mb-3 shadow-sm border-4">

                                        <div class="d-flex justify-content-between align-items-center px-3">
                                            <!-- Tên sản phẩm -->
                                            <h3 class="title" data-bs-toggle="tooltip"
                                                data-bs-title="Đến trang Chi tiết sản phẩm <?= $data['TenSP'] ?>">
                                                <a href="./chitietsp.php?MaSP=<?= $data['MaSP'] ?>"
                                                    class="text-decoration-none" style="color: #005f69;">
                                                    <?= $data['TenSP'] ?>
                                                </a>
                                            </h3>
                                            <!-- Phần trăm khuyến mãi -->
                                            <div class="discount-badge">
                                                10<span>%</span>
                                            </div>
                                        </div>

                                        <div class="bg-flash-info-body">
                                            <!-- mã sản phẩm -->
                                            <p class="content">Mã SP: <strong
                                                    class="ms-3"><?php echo $data['MaSP']; ?></strong></p>
                                            <p class="content">Đã bán: <strong class="ms-3">500 sp</strong></p>

                                            <div class="w-100 d-flex justify-content-end align-items-center px-4">
                                                <!-- Giá gốc và giá khuyến mãi -->
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <del
                                                        class="text-muted me-2 discount-price"><?php echo number_format($data['Giaban'], 0, ",", "."); ?><u>đ</u></del>
                                                    <strong
                                                        class="text-danger me-2 discount-price"><?php echo number_format($data['GiaKM'], 0, ",", "."); ?><u>đ</u></strong>
                                                </div>

                                                <a class="btn btn-dark btn-circle"
                                                    href="./chitietsp.php?MaSP=<?= $data['MaSP'] ?>">
                                                    <i class="fa-regular fa-eye"></i>
                                                </a>
                                            </div>
                                            <!-- Sao đánh giá -->
                                            <div class="discount-ratings">
                                                <?php
                                                // Lấy mã sản phẩm
                                                $productID = $data['MaSP'];
                                                //Lấy số lượng đánh giá
                                                $countRatingResult = mysqli_query($conn, "SELECT COUNT(DiemDG) AS totalRatings FROM danhgia WHERE MaSP = '$productID'");
                                                $countRatingRow = mysqli_fetch_assoc($countRatingResult);
                                                $totalRatings = $countRatingRow['totalRatings'];
                                                //Lấy rating trung bình
                                                $avgRatingResult = mysqli_query($conn, "SELECT AVG(DiemDG) AS avgRating FROM danhgia WHERE MaSP = '$productID'");
                                                $avgRatingRow = mysqli_fetch_assoc($avgRatingResult);
                                                $averageRating = round($avgRatingRow['avgRating'], 0);
                                                ?>
                                                <!-- Đánh giá -->
                                                <p class="content">Đánh giá: <strong class="ms-3"><?= $totalRatings ?>
                                                        lượt</strong></p>
                                                <div class="discount-stars me-4">
                                                    <!-- Rating star -->
                                                    <div class="ratings d-flex flex-column align-items-center">
                                                        <input value="<?= $averageRating ?>" data-size="sm" class="rating"
                                                            data-readonly="true" data-show-clear="false"
                                                            data-show-caption="false">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Hình ảnh sản phẩm -->
                            <div class="col-md-6 item pb-3">
                                <img src="<?php echo "assets/imgs/products/" . $data['Hinhanh'] ?>" alt="">
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Banner Discount Section -->
        <section class="banner-discount-section">
            <div class="container-fluid">
                <img src="assets/imgs/banners/bn_discount.png" alt="">
            </div>
        </section>

        <!-- Voucher Section -->
        <section class="banner-voucher-section">
            <div class="bg-title-voucher text-center mb-4">
                <p class="title-voucher">SĂN VOUCHER KHUYẾN MÃI</p>
            </div>
            <?php
            // Lấy dữ liệu voucher từ cơ sở dữ liệu
            $discountResult = mysqli_query($conn, 'SELECT * FROM khuyenmai ORDER BY NgayKT ASC');
            $discountLst = mysqli_fetch_all($discountResult, MYSQLI_ASSOC);

            // Danh sách các hình ảnh voucher ngẫu nhiên
            $voucherImages = [
                'assets/imgs/vouchers/voucher_1.png',
                'assets/imgs/vouchers/voucher_2.png',
            ];

            // Định nghĩa màu nền cụ thể cho từng hình ảnh voucher
            $voucherBgColors = [
                'assets/imgs/vouchers/voucher_1.png' => '#FBFF00',
                'assets/imgs/vouchers/voucher_2.png' => '#E6FFFF',
            ];
            ?>
            <div class="grid-container">
                <?php foreach ($discountLst as $data): ?>
                    <?php
                    // Chọn ngẫu nhiên một hình ảnh từ mảng voucherImages
                    $randomImage = $voucherImages[array_rand($voucherImages)];

                    // Xác định màu nền cho voucher hiện tại dựa trên hình ảnh đã chọn
                    $backgroundColor = $voucherBgColors[$randomImage];
                    ?>
                    <div class="voucher-card" style="background-color: <?php echo $backgroundColor; ?>;">
                        <div class="voucher-image">
                            <img src="<?php echo $randomImage; ?>" alt="Voucher Image" class="voucher-image">
                        </div>

                        <div class="voucher-details">
                            <div class="voucher-title"><?php echo $data['TenCT']; ?></div>
                            <div class="text"><?php echo $data['DieuKien']; ?></div>
                            <div class="text">Có hiệu lực từ
                                <strong><?php echo date_format(date_create($data['NgayBD']), "d/m/Y"); ?> -
                                    <?php echo date_format(date_create($data['NgayKT']), "d/m/Y"); ?></strong>
                            </div>
                        </div>

                        <button class="voucher-button" onclick="saveIdToLocalStorage('<?php echo $data['MaKM']; ?>')">Lưu</button>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <?php include 'layouts/footer.php'; ?>

    <!-- Scripts -->
    <script src="scripts/header.js"></script>
    <script src="scripts/discount.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>

    <!-- Tooltip -->
    <script>
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    </script>

    <!-- Save data to local storage -->
    <script>
        var savedVoucher = localStorage.getItem('savedVoucher');
        document.localStorage =


            function saveIdToLocalStorage(voucherId) {
                let savedVoucher = localStorage.getItem('savedVoucher');
                if (savedVoucher !== voucherId) {
                    localStorage.setItem('savedVoucher', voucherId);
                    alert('Đã lưu voucher khuyến mãi!');
                } else {
                    alert('Voucher khuyến mãi này đã được lưu!');
                }
            }
    </script>
</body>

</html>