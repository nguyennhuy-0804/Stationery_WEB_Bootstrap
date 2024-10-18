<body>
    <footer>
        <!-- Hình ảnh và thanh chia (Footer trên)-->
        <div class="container-fluid footer-top">
            <div class="footer-top-divider-2"></div>
            <div class="footer-image"></div>
            <div class="footer-bottom-divider"></div>
        </div>

        <!-- Footer chính -->
        <div class="container-fluid footer-bottom text-white py-2">
            <div class="row align-items-start py-2">
                <div class="col-md-6 col-lg-6">
                    <!-- Hình logo -->
                    <img src="assets/imgs/parts/footer_logo.png" alt="" width="400" class="mt-3">

                    <!-- Email -->
                    <p class="text-md text-white mt-3 mx-4">
                        <i class="fa-solid fa-at footer-icon"></i>
                        ueh.edu.vn
                    </p>

                    <!-- Địa chỉ -->
                    <p class="text-md text-white my-3 mx-4">
                        <i class="fa-solid fa-location-dot footer-icon"></i>
                        B1.111 – 279 Đ. Nguyễn Tri Phương, Phường 5, Quận 10, Hồ Chí Minh
                    </p>
                </div>

                <div class="col-md-6 col-lg-6">
                    <div>
                        <p class="text-display-lg-bold-32 text-white">Chính sách</p>
                        <div class="footer-divider-main" />
                    </div>

                    <ul class="policy-list">
                        <li>
                            <a href="<?php echo 'hoidap.php#huongdan' ?>" class="footer-link text-link-lg-18">
                                Hướng dẫn mua hàng
                            </a>
                            <div class="footer-divider-child" />
                        </li>
                        <li>
                            <a href="<?php echo 'hoidap.php#doitra' ?>" class="footer-link text-link-lg-18">
                                Chính sách đổi trả
                            </a>
                            <div class="footer-divider-child" />
                        </li>
                        <li>
                            <a href="<?php echo 'hoidap.php#baomat' ?>" class="footer-link text-link-lg-18">
                                Chính sách bảo mật
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Zalo -->
                <div class="footer-bottom-buttons-btn-zalo">
                    <div class="zalo-btn">
                        <span class="phone-number">8900-4657</span>
                        <div class="zalo-icon">
                            <img src="assets/imgs/buttons/btn_zalo.png" alt="Zalo">
                        </div>
                    </div>
                </div>

                <!-- Phone -->
                <div class="footer-bottom-buttons-btn-phone">
                    <div class="phone-btn">
                        <span class="phone-number">8900-4657</span>
                        <div class="phone-icon">
                            <i class="fas fa-phone-flip"></i>
                        </div>
                    </div>
                </div>
            </div>
    </footer>
</body>