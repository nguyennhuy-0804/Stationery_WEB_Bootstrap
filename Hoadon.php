<?php
include "database/conn.php";
session_start();

// Kết nối đến cơ sở dữ liệu
$server = 'localhost';
$user = 'root';
$pass = '';
$database = 'uehstationery';

try {
    // Tạo kết nối
    $conn = new PDO("mysql:host=$server;dbname=$database;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Lấy ID đơn hàng từ tham số URL
    if (isset($_GET['id'])) {
        $orderId = intval($_GET['id']); // Chuyển đổi ID thành số nguyên để bảo mật

        // Truy vấn để lấy thông tin đơn hàng và chi tiết sản phẩm
        $query = $conn->prepare("
            SELECT dh.*, ctdh.*, sp.TenSP, sp.MaSP, sp.Giaban, sp.GiaKhuyenMai 
            FROM donhang dh 
            JOIN chitietdonhang ctdh ON dh.MaDH = ctdh.MaCTDH 
            JOIN sanpham sp ON ctdh.MaSP = sp.MaSP 
            WHERE dh.id = :orderId
        ");
        $query->bindParam(':orderId', $orderId, PDO::PARAM_INT);
        $query->execute();

        // Khởi tạo mảng để lưu kết quả
        $orderDetails = [];
        $total = 0;

        // Lặp qua các kết quả và lưu vào mảng
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            // Kiểm tra xem có giá khuyến mãi không
            $price = !empty($row['GiaKhuyenMai']) ? $row['GiaKhuyenMai'] : $row['Giaban'];
            $item = [
                'TenSP' => $row['TenSP'],
                'MaSP' => $row['MaSP'],
                'SLuong' => $row['SLuong'],
                'Giaban' => number_format($row['Giaban']),
                'GiaKhuyenMai' => !empty($row['GiaKhuyenMai']) ? number_format($row['GiaKhuyenMai']) : null,
                'ThanhTien' => number_format($price * $row['SLuong']),
            ];
            $orderDetails[] = $item;
            $total += $price * $row['SLuong']; // Tính tổng
        }

        // Trả về dữ liệu dưới dạng JSON
        echo json_encode([
            'orderDetails' => $orderDetails,
            'total' => number_format($total),
        ]);
    } else {
        // Nếu không có ID, trả về lỗi
        echo json_encode(['error' => 'No order ID provided']);
    }
} catch (PDOException $e) {
    // Trả về lỗi kết nối
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Summary</title>

    <!-- Libraries -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- CSS -->
    <link rel="stylesheet" href="css/main.css" />
    <link rel="stylesheet" href="css/reset.css" />
    <link rel="stylesheet" href="css/styles.css" />
    <link rel="stylesheet" href="css/layouts/header.css" />
    <link rel="stylesheet" href="css/layouts/footer.css" />

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" href="assets/css/Hoadon.css">
</head>

<body>
    <!-- Header -->
    <?php include 'layouts/header.php'; ?>

    <div class="container mt-4">
        <!-- Thông tin cá nhân -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-start">
                    <div class="col-12 col-md-3 mb-3 mb-md-0 d-flex justify-content-center">
                        <img src="https://static.vecteezy.com/system/resources/thumbnails/002/387/693/small_2x/user-profile-icon-free-vector.jpg" alt="User Avatar" class="img-fluid rounded-circle" style="max-width: 90%; height: 100%;"> <!-- Ảnh đại diện -->
                    </div>
                    <div class="col-12 col-md-9">
                        <h5 class="card-title custom-inf">THÔNG TIN CÁ NHÂN</h5>
                        <p class="mb-2" style="margin-top: 1rem;"><strong>Họ và tên:</strong> <span id="userName"></span></p>
                        <p class="mb-2"><strong>Giới tính:</strong> <span id="gender"></span></p>
                        <p class="mb-2"><strong>Email:</strong> <span id="email"></span></p>
                        <p class="mb-2"><strong>Địa chỉ:</strong> <span id="address"></span></p>
                        <p class="mb-2"><strong>Số điện thoại:</strong> <span id="phone"></span></p>
                        <p class="mb-2"><strong>Hạng TV:</strong> <span id="membership"></span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Đơn đặt hàng -->
        <h5 class="card-title order-detail d-flex align-items-center">
            <i class="fa-regular fa-file-lines fa-2x me-4"></i>
            Đơn Đặt Hàng
        </h5>
        <div class="card mb-4" id="orderDetails">
            <div class="card-body">
                <!-- Chi tiết đơn hàng sẽ được chèn ở đây -->
            </div>
            <div class="card-footer" id="totalFooter">
                <p class="text-end">Tổng đơn đặt hàng: <span id="totalAmount">0 đ</span></p>
            </div>
        </div>

        <!-- Chọn Giao hàng và thanh toán -->
        <div class="card mb-4">
            <div class="card-body">
                <h5>Hình thức giao hàng:</h5>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="shipping" id="fastShipping" value="fast">
                    <label class="form-check-label" for="fastShipping">
                        Giao hàng nhanh
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="shipping" id="ecoShipping" value="eco">
                    <label class="form-check-label" for="ecoShipping">
                        Giao hàng tiết kiệm
                    </label>
                </div>

                <h5 class="mt-4">Hình thức thanh toán:</h5>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="payment" id="bankPayment">
                    <label class="form-check-label" for="bankPayment">
                        Ngân hàng điện tử
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="payment" id="momoPayment">
                    <label class="form-check-label" for="momoPayment">
                        Momo
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="payment" id="zalopayPayment">
                    <label class="form-check-label" for="zalopayPayment">
                        ZaloPay
                    </label>
                </div>
            </div>
            <div class="card-footer bg-light d-flex justify-content-end align-items-center">
                <p class="mb-0 me-2">Tổng số tiền phải thanh toán: <span id="totalPayment">0 đ</span></p>
                <form action="payment.php" method="POST">
                    <button type="submit" class="btn" style="background-color: #F26F33; color: white;">Thanh Toán</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'layouts/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            const orderId = new URLSearchParams(window.location.search).get('id');

            $.ajax({
                url: 'Hoa don.php?id=' + orderId,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    let orderDetailsHtml = '';
                    $.each(data.orderDetails, function(index, item) {
                        orderDetailsHtml += `
                            <div class="row border-bottom mb-2 product-item">
                                <div class="col-8">
                                    <p><strong>${item.TenSP}</strong><br><small>Mã SP: ${item.MaSP}</small></p>
                                </div>
                                <div class="col-4 text-end d-flex justify-content-between"> 
                                    <div>SL: ${item.SLuong} x</div>
                                    <div>${item.Giaban} đ</div>
                                    <div>= ${item.ThanhTien} đ</div>
                                </div>
                            </div>`;
                    });
                    $('#orderDetails .card-body').html(orderDetailsHtml);
                    $('#totalAmount').text(data.total + ' đ');
                    $('#totalPayment').text(data.total + ' đ');
                    updateTotalPayment(); // Cập nhật tổng tiền thanh toán ban đầu
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error fetching order details:', textStatus, errorThrown);
                }
            });

            // Cập nhật tổng tiền thanh toán
            function updateTotalPayment() {
                const shippingMethod = $('input[name="shipping"]:checked').val();
                let total = parseFloat($('#totalAmount').text()) || 0;

                if (shippingMethod === 'fast') {
                    total += 20000; // Cộng thêm 20.000 cho giao hàng nhanh
                } else if (shippingMethod === 'eco') {
                    total += 10000; // Cộng thêm 10.000 cho giao hàng tiết kiệm
                }

                $('#totalPayment').text(total + ' đ');
            }

            // Lắng nghe sự thay đổi của radio button cho hình thức giao hàng
            $('input[name="shipping"]').change(function() {
                updateTotalPayment(); // Cập nhật tổng tiền khi thay đổi hình thức giao hàng
            });

            // Lấy ID người dùng từ URL
            const userId = new URLSearchParams(window.location.search).get('id');

            $.ajax({
                url: 'get_user.php?id=' + userId,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.error) {
                        console.error('Error fetching user data:', data.error);
                    } else {
                        $('#userName').text(data.HoTen);
                        $('#gender').text(data.GioiTinh);
                        $('#email').text(data.Email);
                        $('#address').text(data.DiaChi);
                        $('#phone').text(data.SDT);
                        $('#membership').text(data.HangTV);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error fetching user data:', textStatus, errorThrown);
                }
            });
        });
    </script>
</body>

</html>