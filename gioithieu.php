<?php
include "database/conn.php";
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giới thiệu</title>

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

    <link rel="stylesheet" href="css/gioithieu.css">
</head>

<body>
    <!-- Header -->
    <?php include 'layouts/header.php'; ?>

    <div class="outlet">
        <div class="header-gt">
            <img id="merueh-logo" src="./assets/imgs/abouts/MERUEH.PNG" alt="MERUEH" class="logo">
        </div>

        <section class="introduction">
            <div class="container">
                <h2>GIỚI THIỆU</h2>
                <p>
                    <b>UEH STATIONERY</b> là trang giao dịch bán hàng trực tuyến trực thuộc Đại học Kinh tế TP. Hồ Chí Minh.
                    Trang được ra mắt lần đầu tiên vào ngày 21 tháng 9 năm 2024 với sứ mệnh từ việc bán
                    các sản phẩm văn phòng, sách, giáo trình học tập, tài liệu tham khảo,... không chỉ giúp khuyến khích mọi
                    người nâng cao trí thức mà còn gây quỹ chung tay góp sức với các hoàn cảnh khó khăn.
                </p>
                <p>
                    Từ một trang web bán hàng nhỏ bé giờ đây chúng tôi đã trở thành nơi được nhiều sinh viên trong và ngoài
                    nhà trường tin tưởng chọn lựa làm sự ưu tiên hàng đầu của họ khi cần mua sách hoặc các sản phẩm văn
                    phòng khác. Mặc dù, trang thành lập cách đây không lâu nhưng chúng tôi đã đạt được rất nhiều thành tựu
                    to lớn trong lĩnh vực bán lẻ nói chung và văn phòng phẩm nói riêng. Đối tác của chúng tôi là các nhà
                    cung cấp từ trong nước cho đến nội địa điển hình như Thiên Long, Casio, Deli,... hay cả Double A.
                </p>
            </div>
        </section>

        <div class="container text-center">
            <div class="row align-items-center">
                <div class="col-12 col-md-6">
                    <div class="thumbnail big-thumbnail"
                        style="background: linear-gradient(to bottom, #005f69 0%, #ffffff 50%, #f26f33 100%);">
                        <a href="#L1">
                            <img src="./assets/imgs/abouts/MHX2023.png"
                                class="img-fluid" alt="MHX" />
                        </a>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="thumbnail small-thumbnail"
                        style="background: linear-gradient(to bottom, #f26f33 0%, #ffffff 95%, #ffffff 100%);">
                        <a href="#L5">
                            <img src="./assets/imgs/abouts/GOVIRLUNTEER.png" class="img-fluid"
                                alt="GOVIRLUNTEER" />
                        </a>
                    </div>
                    <div class="thumbnail small-thumbnail"
                        style="background: linear-gradient(to bottom, #ffffff 0%, #005f69 100%);">
                        <a href="#L3">
                            <img src="./assets/imgs/abouts/XTN.png"
                                class="img-fluid" alt="XTN22" />
                        </a>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="thumbnail small-thumbnail"
                        style="background: linear-gradient(to bottom, #005f69 0%, #ffffff 95%, #ffffff 100%);">
                        <a href="#L4">
                            <img src="./assets/imgs/abouts/XSC.png" class="img-fluid"
                                alt="XSC" />
                        </a>
                    </div>
                    <div class="thumbnail small-thumbnail"
                        style="background: linear-gradient(to bottom, #ffffff 0%, #f26f33 100%);">
                        <a href="#L2">
                            <img src="./assets/imgs/abouts/SACHA.png"
                                class="img-fluid" alt="TLP" />
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="orange-line"></div>
        <section class="partners">
            <div class="partner-logos">
                <img src="./assets/imgs/abouts/DELI.png" alt="Deli">
                <img src="./assets/imgs/abouts/CASIO.png" alt="Casio">
                <img src="./assets/imgs/abouts/DOUBLEA.png" alt="Double A">
                <img src="./assets/imgs/abouts/THIENLONG.png" alt="Thiên Long">
            </div>
            <a class="cta-button">Đối tác</a>
        </section>
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