<?php
include "database/conn.php";
session_start();

//* Nếu chưa đăng nhập -> Chuyển tới trang Login
if (!isset($_SESSION['mySession'])) {
    header('location:login.php');
    exit();
}
 
if (isset($_POST['addRatingBtn'])) {

    $str = rand();
    $MaDG = "DG" . md5($str);

    $userID = $_SESSION['user']['MaTV'];
    $productID = $_POST['MaSP'];
    $DiemDG = $_POST['DiemDG'];
    $Binhluan = $_POST['Binhluan'];

    //Thêm dữ liệu đã nhập vào bảng taikhoan
    $sql1 = "INSERT INTO danhgia (MaDG,MaTV,MaSP,DiemDG,Binhluan) VALUES('$MaDG','$userID','$productID','$DiemDG','$Binhluan'); ";
    mysqLi_query($conn, $sql1);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết sản phẩm</title>

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

    <!--Xzoom-->
    <link rel="stylesheet" href="https://unpkg.com/xzoom/dist/xzoom.css" crossorigin="anonymous">
    <script src="https://unpkg.com/xzoom/dist/xzoom.min.js"></script>

    <link rel="stylesheet" href="css/chitietsp.css" />
</head>

<body>
    <!-- Header -->
    <?php include 'layouts/header.php'; ?>

    <?php
    $MaSP = $_GET['MaSP'];
    $productsResult = mysqli_query($conn, "SELECT * FROM sanpham WHERE MaSP='$MaSP'");
    $productRow = mysqli_fetch_assoc($productsResult);

    // lấy mã loại của sản phẩm hiện tại
    $categoryID = $productRow['Maloai'];

    // Truy vấn sản phẩm từ loại sản phẩm 
    $relatedProductsResult = mysqli_query($conn, "SELECT * FROM sanpham WHERE Maloai = '$categoryID' AND MaSP != '$MaSP'");

    // lấy các rating của sp
    $ratingResult = mysqli_query($conn, "SELECT * FROM danhgia WHERE MaSP = '$MaSP' ORDER BY MaDG ASC");

    // lấy số lượng đánh giá của sp
    $countRatingResult = mysqli_query($conn, "SELECT COUNT(DiemDG) AS totalRatings FROM danhgia WHERE MaSP = '$MaSP'");
    $countRatingRow = mysqli_fetch_assoc($countRatingResult);
    $totalRatings = $countRatingRow['totalRatings']; // Số lượng đánh giá

    // rating trung bình của mỗi sp
    $avgRatingResult = mysqli_query($conn, "SELECT AVG(DiemDG) AS avgRating FROM danhgia WHERE MaSP = '$MaSP'");
    $avgRatingRow = mysqli_fetch_assoc($avgRatingResult);
    $averageRating = round($avgRatingRow['avgRating'], 0); // làm tròn
    ?>

    <div class="container my-5">
        <div class="img-thumbnail p-3">
            <div class="row g-4">
                <!-- Bên trái: Hình ảnh sản phẩm -->
                <div class="col-lg-5 col-md-6 col-sm-12 bg-light py-3 rounded"> <!--ảnh to-->
                    <img src="./assets/imgs/products/<?= $productRow['Hinhanh'] ?>" xoriginal="./assets/imgs/products/<?= $productRow['Hinhanh'] ?>"
                        class="img-fluid mb-3 xzoom" alt="Hình ảnh <?= $productRow['TenSP'] ?>">

                    <div class="d-flex justify-content-center xzoom-thumbs"> <!--2 ảnh nhỏ ở dưới-->
                        <a href="./assets/imgs/products/<?= $productRow['Hinhanh'] ?>">
                            <img src="./assets/imgs/products/<?= $productRow['Hinhanh'] ?>"
                                class="img-thumbnail img-fluid me-2 xzoom-gallery" alt="Thumbnail">

                        </a>
                        <a href="./assets/imgs/products/<?= $productRow['Hinhanh'] ?>">
                            <img src="./assets/imgs/products/<?= $productRow['Hinhanh'] ?>"
                                class="img-thumbnail img-fluid me-2 xzoom-gallery" alt="Thumbnail">
                        </a>
                    </div>
                </div>

                <!-- Bên phải: Thông tin sản phẩm -->
                <div class="col-lg-7 col-md-6 col-sm-12 align-self-start mt-5 ">
                    <div class="product-info-container">
                        <h3 class="fw-bold my-2">
                            <?= $productRow['TenSP'] ?>
                        </h3> <!--tên sản phẩm-->

                        <p>
                            <?php if ($productRow['GiaKM'] != NULL) { ?>
                                <span class="special" id="discount">SALE</span>
                            <?php } ?>
                            <?php if ($productRow['HotTrend'] != 0) { ?>
                                <span class="special" id="new">HOT</span>
                            <?php } ?>
                            <?php if ($productRow['BestSeller'] != 0) { ?>
                                <span class="special" id="new">BÁN CHẠY</span>
                            <?php } ?>
                        </p>

                        <p class="pt-3">
                            <strong>Mô tả:</strong>
                            <?= $productRow['Mota'] ?><!--mô tả sản phẩm-->
                        </p>

                        <p class="pt-2 pb-3">
                            <strong>Giá: </strong> <!--giá sản phẩm-->
                            <?php if ($productRow['GiaKM'] != NULL) { ?>
                                <span class="text-decoration-line-through">
                                    <?= number_format($productRow['Giaban'], 0, ",", ".") ?><sup>đ</sup>
                                </span>
                            <?php } ?>
                            <span class="text-danger h4 ps-2">
                                <strong>
                                    <?= number_format($productRow['GiaKM'] ? $productRow['GiaKM'] : $productRow['Giaban'], 0, ",", ".") ?><sup>đ</sup>
                                </strong>
                            </span>
                        </p>

                        <form role="form" action="giohang.php" method="post">
                            <div class="form-group mb-3">
                                <label class="form-label"><strong>Màu sắc:</strong></label>
                                <div class="btn-group" role="group">
                                    <input type="radio" class="btn-check" name="color" id="black">
                                    <label class="btn btn-outline-dark" for="black">Đen</label>

                                    <input type="radio" class="btn-check" name="color" id="pink" autocomplete="off">
                                    <label class="btn btn-outline-danger" for="pink">Hồng</label>

                                    <input type="radio" class="btn-check" name="color" id="blue" autocomplete="off">
                                    <label class="btn btn-outline-primary" for="blue">Xanh</label>
                                </div>
                            </div>

                            <div class="number form-group mb-3 mt-2">
                                <label><strong>Số lượng:</strong></label>
                                <input type="number" class="input-sm rounded" id="soluong" name="soluong" value="1" min="1" />
                            </div>

                            <!-- Button -->
                            <div class="mb-3">
                                <button type="submit" class="btn btn-warning me-2" name="addtocartbtn" value="1">Thêm vào giỏ hàng</button>
                                                            <!-- Trường Ẩn để Truyền Dữ Liệu Sản Phẩm -->
                                <input type="hidden" name="hinhanh" value="<?= $productRow['Hinhanh'] ?>">
                                <input type="hidden" name="tensp" value="<?= $productRow['TenSP'] ?>">
                                <input type="hidden" name="giaban" value="<?= $productRow['Giaban'] ?>">
                                <input type="hidden" name="giakm" value="<?= $productRow['GiaKM'] ?>">
                                <input type="hidden" name="MaSP" value="<?= $productRow['MaSP'] ?>"> <!-- Product ID -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Rating-->
            <div class="ratings mt-3">
                <input value="<?= $averageRating ?>" class="rating" data-readonly="true" data-show-clear="false">
            </div>

            <div class="well mt-3">
                <p class="d-inline-flex gap-1">
                    <a class="btn btn-success" data-bs-toggle="collapse" href="#addRating" role="button"
                        aria-expanded="false">
                        Thêm đánh giá
                    </a>
                </p>

                <div class="collapse" id="addRating">
                    <div class="card-body img-thumbnail p-4 bg-light">
                        <form action="chitietsp.php?MaSP=<?= $productRow['MaSP'] ?>" method="POST">
                            <div class="mb-3"> <!-- hiện tên tv đang đánh giá -->
                                <label for="tentv" class="form-label"><?= $_SESSION['user']['TenTV'] ?></label>
                            </div>

                            <div class="mb-3"> <!-- lấy điểm đánh giá -->
                                <label for="DiemDG" class="form-label">Điểm đánh giá</label>
                                <input value="5" name="DiemDG" class="rating" data-readonly="false"
                                    data-show-clear="false" data-step="1">
                            </div>

                            <div class="mb-3"> <!-- lấy bình luận -->
                                <label for="Binhluan" class="form-label">Bình luận</label>
                                <textarea class="form-control" id="Binhluan" name="Binhluan" rows="3"></textarea>
                            </div>

                            <button type="submit" class="btn btn-success" name="addRatingBtn">Gửi đánh giá</button>

                            <!-- mã sp -->
                            <input type="hidden" name="MaSP" value="<?= $productRow['MaSP'] ?>">
                        </form>
                    </div>
                </div>
            </div>

            <hr />

            <div class="row">
                <?php
                while ($ratingRow = mysqli_fetch_assoc($ratingResult)) {
                    $userRatingID = $ratingRow['MaTV']; // lấy mã tv đánh giá

                    // lấy tên thành viên đánh giá theo mã tv
                    $userRatingResult = mysqli_query($conn, "SELECT * FROM thanhvien WHERE MaTV = '$userRatingID' ORDER BY MaTV ASC LIMIT 3");
                    $userRow = mysqli_fetch_assoc($userRatingResult);
                ?>
                    <div class="col-md-12">
                        <p>
                            <input value=" <?= $ratingRow['DiemDG'] ?>" class="rating" data-readonly="true"
                                data-show-clear="false"> <!-- điểm đánh giá -->
                        </p>
                        <h5>
                            <?= $userRow['TenTV'] ?> <!-- tên tv -->
                        </h5>
                        <p>
                            <?= $ratingRow['Binhluan'] ?> <!-- bình luận -->
                        </p>
                    </div>
                <?php } ?>
            </div>
        </div>

        <div id="danhmucsp-content" class="container-fluid">
            <h2 class="my-5 text-uppercase fw-bold">Có thể bạn muốn mua</h2>
            <div class="container text-center">
                <div class="row align-items-center">
                    <?php
                    while ($relatedProductRow = mysqli_fetch_array($relatedProductsResult)) {
                        $relatedProductID = $relatedProductRow['MaSP'];

                        // Lấy đánh giá và số lượng đánh giá cho sản phẩm liên quan
                        $countRatingResult = mysqli_query($conn, "SELECT COUNT(DiemDG) AS totalRatings FROM danhgia WHERE MaSP = '$relatedProductID'");
                        $countRatingRow = mysqli_fetch_assoc($countRatingResult);
                        $totalRatings = $countRatingRow['totalRatings'];

                        $avgRatingResult = mysqli_query($conn, "SELECT AVG(DiemDG) AS avgRating FROM danhgia WHERE MaSP = '$relatedProductID'");
                        $avgRatingRow = mysqli_fetch_assoc($avgRatingResult);
                        $averageRating = round($avgRatingRow['avgRating'], 0);
                    ?>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                            <!--thông tin sp, mỗi cái div này là 1 sp-->
                            <form action="giohang.php" method="post">
                                <div class="card position-relative">
                                    <!-- Hiển thị huy hiệu giảm giá nếu sản phẩm có giảm giá (GiaKM) -->
                                    <?php if ($relatedProductRow['GiaKM'] != NULL) { ?>
                                        <div class="discount-badge">SALE</div>
                                    <?php } ?>

                                    <img src="./assets/imgs/products/<?= $relatedProductRow['Hinhanh'] ?>"
                                        class="card-img-top product-img img-fluid"
                                        alt="Hình ảnh <?= $relatedProductRow['TenSP'] ?>">

                                    <div class="card-body">
                                        <h5 href="chitietsp?id=<?= $relatedProductRow['MaSP'] ?>" class="card-title"
                                            data-bs-toggle="tooltip"
                                            data-bs-title="Đến trang Chi tiết sản phẩm <?= $relatedProductRow['TenSP'] ?>">
                                            <a href="./chitietsp.php?MaSP=<?= $relatedProductRow['MaSP'] ?>"
                                                class="text-decoration-none" style="color: #005f69;">
                                                <?= $relatedProductRow['TenSP'] ?>
                                            </a>
                                        </h5>

                                        <p class="card-text">
                                            <?php if ($relatedProductRow['GiaKM'] != NULL) { ?>
                                                <span class="text-decoration-line-through">
                                                    <?= number_format($relatedProductRow['Giaban'], 0, ",", ".") ?><sup>đ</sup>
                                                </span>
                                            <?php } ?>
                                            <span class="text-danger"><strong>
                                                    <?= number_format($relatedProductRow['GiaKM'] ? $relatedProductRow['GiaKM'] : $relatedProductRow['Giaban'], 0, ",", ".") ?><sup>đ</sup>
                                                </strong></span>
                                        </p>

                                        <!-- Buttons thêm giỏ hàng và danh sách yêu thích -->
                                        <div class="btn-group" role="group">
                                            <button type="submit" class="btn btn-success" name="addtocartbtn" value="1"
                                                data-bs-toggle="tooltip" data-bs-title="Thêm vào giỏ hàng">
                                                <!--button thêm giỏ hàng nè-->
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                                                </svg>
                                            </button>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="tooltip"
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
                                            <div class="progress-bar fw-bold" style="width: 80%">
                                                <span>Đã bán: 60</span> <!--progress-->
                                            </div>
                                        </div>

                                        <!-- Bootstrap Rating -->
                                        <div class="ratings d-flex flex-column align-items-center">
                                            <p class="mb-1" style="font-size: 0.85rem;">
                                                <?= $totalRatings ?> đánh giá
                                            </p>
                                            <input value="<?= $averageRating ?>" data-size="xs" class="rating"
                                                data-readonly="true" data-show-clear="false" data-show-caption="false">
                                        </div>
                                        <!-- Trường Ẩn để Truyền Dữ Liệu Sản Phẩm -->
                                        <input type="hidden" name="hinhanh" value="<?= $relatedProductRow['Hinhanh'] ?>">
                                        <input type="hidden" name="tensp" value="<?= $relatedProductRow['TenSP'] ?>">
                                        <input type="hidden" name="giaban" value="<?= $relatedProductRow['Giaban'] ?>">
                                        <input type="hidden" name="giakm" value="<?= $relatedProductRow['GiaKM'] ?>">
                                        <input type="hidden" name="MaSP" value="<?= $relatedProductRow['MaSP'] ?>">
                                        <!-- Product ID -->
                                        <input type="hidden" name="soluong" value="1">
                                    </div>
                                </div>
                            </form>
                        </div>
                    <?php }
                    ?>
                </div>
            </div>
        </div>
    </div>



    <!-- Footer -->
    <?php include 'layouts/footer.php'; ?>

    <!-- Scripts -->
    <script src="scripts/header.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script>
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    </script>
    <script>
        $(".xzoom, .xzoom-gallery").xzoom({
            tint: '#333',
            Xoffset: 15
        });
    </script>
</body>

</html>