<?php
include "database/conn.php";

session_start();
//Nếu chưa đăng nhập -> Chuyển tới trang Login
// if (!isset($_SESSION['mySession'])) {
//     header('location:login.php');
//     exit();
// }

?>

<!DOCTYPE html>
<html lang="vi">

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

    <!-- JS -->
    <script src="carousel/vendors/jquery.min.js"></script>
    <script src="carousel/owlcarousel/owl.carousel.js"></script>

    <link rel="stylesheet" href="css/question.css">
</head>

<body>
    <!-- Header -->
    <?php include 'layouts/header.php'; ?>

    <div class="outlet">
        <div class="container">
            <h1 class="title" id="huongdan">HƯỚNG DẪN MUA HÀNG</h1>

            <div class="step">
                <h2>Bước 1: Đăng nhập/Đăng ký tài khoản</h2>
                <ul>
                    <li>Đăng nhập bằng tài khoản và mật khẩu đã đăng ký trước đó. Trong trường hợp chưa có tài khoản,
                        Quý
                        khách có thể chọn “Đăng ký” để đăng ký tài khoản tại UEH Stationery Website.</li>
                    <li>Sau khi đã kê khai đầy đủ thông tin yêu cầu, Quý khách có thể bấm vào chữ “Đăng ký” và “Xác
                        nhận” để
                        hoàn tất quá trình đăng ký.</li>
                </ul>
            </div>

            <div class="step">
                <h2>Bước 2: Tìm kiếm/lựa chọn sản phẩm</h2>
                <p>Quý khách có thể tìm sản phẩm theo 3 cách:</p>
                <ul>
                    <li>Gõ tên sản phẩm vào thanh tìm kiếm.</li>
                    <li>Tìm theo danh mục sản phẩm.</li>
                    <li>Tìm theo các sản phẩm hot/bán chạy.</li>
                </ul>
            </div>

            <div class="step">
                <h2>Bước 3: Thêm sản phẩm vào giỏ hàng</h2>
                <p>Khi đã tìm được sản phẩm mong muốn, Quý khách bấm vào hình hoặc tên sản phẩm để vào được trang thông
                    tin
                    chi tiết của sản phẩm, sau đó:</p>
                <ul>
                    <li>Kiểm tra thông tin sản phẩm: giá/thông tin khuyến mãi/màu sắc.</li>
                    <li>Chọn số lượng muốn mua.</li>
                    <li>Thêm sản phẩm vào giỏ hàng hoặc chọn mua ngay.</li>
                </ul>
            </div>

            <div class="step">
                <h2>Bước 4: Kiểm tra giỏ hàng và đặt hàng</h2>
                <p>Sau khi đã lựa chọn xong sản phẩm, Quý khách kiểm tra lại sản phẩm và số lượng có nhu cầu để điều
                    chỉnh
                    cho phù hợp bằng cách: Bấm “Xem Giỏ hàng” hoặc “Thanh toán” để bắt đầu đặt hàng.</p>
            </div>

            <div class="step">
                <h2>Bước 5: Chọn hình thức giao hàng, phương thức thanh toán</h2>
                <ul>
                    <li>Chọn hình thức giao hàng: nhập địa chỉ giao hàng mong muốn, chọn hình thức giao nhanh hay giao
                        tiết
                        kiệm.</li>
                    <li>Chọn phương thức thanh toán: Chọn 01 trong các phương thức thanh toán như: Thanh toán qua Cổng
                        thanh
                        toán (Momo, ZaloPay và ngân hàng trực tuyến).</li>
                    <li>Sau khi hoàn tất quá trình chọn phương thức thanh toán, vui lòng kiểm tra lại các thông tin sau:
                        địa
                        chỉ nhận hàng, điện thoại, email, giá khuyến mãi, tổng tiền.</li>
                    <li>Nếu các thông tin trên đã chính xác, Quý khách vui lòng bấm “Đặt mua”, hệ thống sẽ bắt đầu tiến
                        hành
                        tạo đơn hàng dựa trên các thông tin Quý khách đã đăng ký.</li>
                </ul>
            </div>

            <div class="step">
                <h2>Bước 6: Theo dõi tình trạng đơn hàng</h2>
                <p>Sau khi đặt hàng thành công, Quý khách có thể theo dõi tiến trình xử lý của đơn hàng ở phần đơn hàng
                    hoặc
                    trạng thái được cập nhật qua email.</p>

                <!-- Chính sách đổi trả -->
                <hr class="orange-divider">
                <h1 class="title" id="doitra">CHÍNH SÁCH ĐỔI TRẢ</h1>
                <div class="policy">
                    <h2>1. Quy định đổi/trả hàng:</h2>
                    <p>Quý khách được đổi/trả sản phẩm mới cùng loại nếu sản phẩm gặp sự cố do lỗi kỹ thuật của nhà sản
                        xuất
                        hoặc đặt lộn kích thước, màu sắc. Đổi với trường hợp đổi hàng chỉ được một lần duy nhất cho một
                        đơn
                        hàng, không đổi sang sản phẩm khác, hàng sẽ được đổi trong 5 - 7 ngày. Sản phẩm chỉ được đổi/trả
                        sau
                        khi tuân thủ theo các điều kiện sau đây:</p>
                    <ul>
                        <li>Hàng hóa được xác định nguồn gốc mua của công ty chúng tôi.</li>
                        <li>Trường hợp nhận hàng sau tối đa 07 ngày và có video quay minh chứng.</li>
                        <li>Hàng hóa nhận lại không bị lỗi hình thức (trầy, xước, móp méo, v.v...) hoặc bị hư hại trong
                            quá
                            trình vận chuyển, không sử dụng được ngay khi được giao.</li>
                        <li>Sản phẩm giao không đúng theo đơn đặt hàng (màu, size,...).</li>
                        <li>Hàng hóa nhận lại phải còn đầy đủ linh phụ kiện kèm theo, tặng phẩm (nếu có), các chương
                            trình
                            khuyến mãi, các chứng từ kèm theo như (Chứng từ mua hàng, chứng từ thanh toán, hóa đơn
                            VAT,...).
                        </li>
                    </ul>
                </div>

                <div class="policy">
                    <h2>2. Chính sách hoàn tiền</h2>
                    <ul>
                        <li>Đối với trường hợp thanh toán trước, khách hàng sẽ được hoàn tiền khi hàng nhận bị lỗi do
                            sản
                            xuất và khách hàng trả hàng không có nhu cầu đổi sang sản phẩm khác nhưng vẫn tính phí ship
                            liên
                            quan.</li>
                        <li>Thời gian hoàn tiền: từ 07 đến 15 ngày kể từ khi shop nhận được hàng trả từ khách hàng. Tiền
                            được hoàn vào tài khoản cá nhân của khách hàng cung cấp.</li>
                    </ul>
                </div>

                <div class="policy">
                    <h2>3. Phương thức đổi trả sản phẩm</h2>
                    <ul>
                        <li>Liên hệ qua hotline.</li>
                        <li>Cung cấp đầy đủ minh chứng.</li>
                        <li>UEH Station sẽ tiếp nhận và xử lý trong vòng 5 đến 7 ngày.</li>
                    </ul>
                </div>
                <hr class="orange-divider">
                <h1 class="title" id="baomat">CHÍNH SÁCH BẢO MẬT</h1>
                <div class="policy">
                    <h2>1. Thông tin khách hàng đối với bên thứ ba</h2>
                    <p>UEH Stationery cam kết không cung cấp thông tin khách hàng với bất kì bên thứ ba nào, trừ những
                        trường hợp sau:</p>
                    <ul>
                        <li>Các đối tác là bên cung cấp dịch vụ liên quan đến thực hiện đơn hàng và chỉ giới hạn trong
                            phạm
                            vi thông tin cần thiết cũng như áp dụng các quy định đảm bảo an ninh, bảo mật các thông tin
                            cá
                            nhân.</li>
                        <li>UEH Stationery có thể sử dụng dịch vụ từ một nhà cung cấp dịch vụ là bên thứ ba để thực hiện
                            một
                            số hoạt động liên quan đến trang thương mại điện tử. Khi đó bên thứ ba này có thể truy cập
                            hoặc
                            xử lý các thông tin cá nhân trong quá trình cung cấp các dịch vụ đó. UEH Stationery yêu cầu
                            các
                            bên thứ ba này tuân thủ mọi quy luật về bảo vệ thông tin cá nhân liên quan và các yêu cầu về
                            an
                            ninh liên quan đến thông tin cá nhân.</li>
                        <li>Yêu cầu pháp lý: UEH Stationery có thể tiết lộ các thông tin cá nhân nếu điều đó do luật
                            pháp
                            yêu cầu, tuân thủ các quy trình pháp lý.</li>
                    </ul>
                </div>

                <div class="policy">
                    <h2>2. An toàn dữ liệu</h2>
                    <p>UEH Stationery luôn nỗ lực để giữ an toàn thông tin cá nhân của khách hàng bằng nhiều biện pháp
                        an
                        toàn, bao gồm:</p>
                    <ul>
                        <li>Bảo đảm an toàn trong môi trường vận hành: UEH Stationery lưu trữ thông tin cá nhân khách
                            hàng
                            trong môi trường vận hành an toàn và chỉ có nhân viên, đại diện và nhà cung cấp dịch vụ có
                            thể
                            truy cập trên cơ sở cần phải biết. UEH Stationery tuân theo các quy định của pháp luật trong
                            việc bảo mật thông tin cá nhân khách hàng.</li>
                        <li>Trong trường hợp máy chủ lưu trữ thông tin bị hacker tấn công dẫn đến mất mát dữ liệu cá
                            nhân
                            khách hàng, UEH Stationery sẽ có trách nhiệm thông báo vụ việc cho cơ quan chức năng để điều
                            tra
                            xử lý kịp thời và thông báo cho khách hàng được biết.</li>
                        <li>Các thông tin thanh toán: được bảo mật theo tiêu chuẩn ngành.</li>
                    </ul>
                </div>

                <div class="policy">
                    <h2>3. Quyền lợi của khách hàng đối với thông tin cá nhân</h2>
                    <ul>
                        <li>Khách hàng có quyền cung cấp thông tin cá nhân cho UEH Stationery và có thể thay đổi quyết
                            định
                            đó vào bất cứ lúc nào.</li>
                        <li>Khách hàng có quyền tự kiểm tra, cập nhật, điều chỉnh thông tin cá nhân của mình bằng cách
                            đăng
                            nhập vào tài khoản và chỉnh sửa thông tin cá nhân hoặc yêu cầu UEH Stationery thực hiện việc
                            này.</li>
                    </ul>
                </div>

                <div class="policy">
                    <h2>4. Yêu cầu xóa bỏ thông tin cá nhân</h2>
                    <ul>
                        <li>Khách hàng có quyền yêu cầu xóa bỏ hoàn toàn các thông tin cá nhân lưu trữ trên hệ thống của
                            UEH
                            Stationery. Khách hàng gửi thư điện tử về địa chỉ shop@ueh.edu.vn để yêu cầu xóa bỏ thông
                            tin cá
                            nhân hoàn toàn khỏi hệ thống.</li>
                    </ul>
                </div>

                <div class="policy">
                    <h2>5. Thông tin liên hệ</h2>
                    <p>Nếu bạn có câu hỏi hoặc bất kỳ thắc mắc nào về Chính sách bảo mật của UEH Stationery Website, xin
                        vui
                        lòng liên hệ qua hotline.</p>
                    <hr class="divider">
                </div>
            </div>
        </div>

        <!-- Footer -->
        <?php include 'layouts/footer.php'; ?>

        <!-- Scripts -->
        <script src="scripts/header.js"></script>
        <script src="scripts/homepage.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
</body>

</html>