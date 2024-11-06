<?php
include "database/conn.php";
session_start();

// Nếu chưa đăng nhập -> Chuyển tới trang Login
if (!isset($_SESSION['mySession'])) {
    header('location:login.php'); // Chuyển hướng đến trang đăng nhập
    exit();
}

// Lấy ID người dùng từ session
$userID = $_SESSION['user']['MaTV'] ?? 0;

$orderDetails = [];
$total = 0;
$shippingCost = 0;

// Nếu chưa đăng nhập -> Chuyển tới trang Login
if (!isset($_SESSION['mySession'])) {
    header('location:login.php');
    exit();
}

if (isset($userID)) {
    // Truy vấn để lấy giỏ hàng của người dùng
    $cartQuery = $conn->prepare("SELECT MaGH FROM giohang WHERE MaTV = ? AND TinhTrang = 1");
    $cartQuery->bind_param('i', $userID);
    $cartQuery->execute();
    $cartResult = $cartQuery->get_result();

    if ($cartRow = $cartResult->fetch_assoc()) {
        $cartID = $cartRow['MaGH']; // Lấy mã giỏ hàng hiện tại

        // Lấy thông tin sản phẩm trong giỏ hàng
        $itemQuery = $conn->prepare("
            SELECT chitietgiohang.*, sanpham.TenSP, sanpham.MaSP, sanpham.Giaban, sanpham.GiaKM, sanpham.HinhAnh 
            FROM chitietgiohang 
            JOIN sanpham ON chitietgiohang.MaSP = sanpham.MaSP 
            WHERE chitietgiohang.MaGH = ?
        ");
        $itemQuery->bind_param('i', $cartID);
        $itemQuery->execute();
        $itemResult = $itemQuery->get_result();

        while ($row = $itemResult->fetch_assoc()) {
            $price = !empty($row['GiaKM']) ? $row['GiaKM'] : $row['Giaban'];
            $item = [
                'TenSP' => $row['TenSP'],
                'MaSP' => $row['MaSP'],
                'SLuong' => $row['SoLuong'],
                'Giaban' => number_format($row['Giaban']),
                'GiaKhuyenMai' => !empty($row['GiaKM']) ? number_format($row['GiaKM']) : null,
                'ThanhTien' => number_format($price * $row['SoLuong']),
                'HinhAnh' => $row['HinhAnh'] ?? 'default-image.jpg',
            ];
            $orderDetails[] = $item;
            $total += $price * $row['SoLuong'];
        }
    } else {
        echo '<p class="text-danger">Giỏ hàng trống hoặc không tồn tại.</p>';
        exit;
    }
} else {
    echo '<p class="text-danger">Không có người dùng đăng nhập.</p>';
    exit;
}

// Tính toán tổng thanh toán
$totalPayment = $total + $shippingCost; // Tổng thanh toán
?>

<?php
// Truy vấn giỏ hàng hiện tại của người dùng
$cartResult = mysqli_query($conn, "SELECT * FROM giohang WHERE MaTV = '$userID' AND TinhTrang = 1 LIMIT 1");
$cartRow = mysqli_fetch_assoc($cartResult);

if (!$cartRow) {
    echo "Giỏ hàng của bạn không có sản phẩm nào.";
    exit();
}

$cartID = $cartRow['MaGH']; // Lấy mã giỏ hàng

// Lấy chi tiết giỏ hàng để hiển thị
$cartDetailsResult = mysqli_query($conn, "SELECT chitietgiohang.*, sanpham.TenSP, sanpham.Hinhanh, sanpham.Giaban, sanpham.GiaKM
                                          FROM chitietgiohang
                                          JOIN sanpham ON chitietgiohang.MaSP = sanpham.MaSP
                                          WHERE chitietgiohang.MaGH = '$cartID'
                                          ORDER BY chitietgiohang.MaCTGH ASC");

$total = 0; // Khởi tạo biến tổng giá trị hóa đơn
$orderDetails = []; // Khởi tạo mảng lưu trữ chi tiết đơn hàng

while ($cartDetailsRow = mysqli_fetch_assoc($cartDetailsResult)) {
    $cartDetailsRow['Thanhtien'] = $cartDetailsRow['GiaKM'] ? $cartDetailsRow['GiaKM'] : $cartDetailsRow['Giaban'];
    $cartDetailsRow['ThanhTien'] = $cartDetailsRow['Thanhtien'] * $cartDetailsRow['SoLuong'];
    $total += $cartDetailsRow['ThanhTien'];
    $orderDetails[] = $cartDetailsRow;
}

$totalPayment = $total; // Tổng tiền thanh toán ban đầu
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn đặt hàng</title>

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
    <link rel="stylesheet" href="css/giohang.css" />
    <link rel="stylesheet" href="css/Hoadon.css" />
</head>

<body>
    <!-- Header -->
    <?php include 'layouts/header.php'; ?>

    <div class="container">
        <!-- Phần thông tin người dùng -->
        <div class="profile-section">
            <h2>THÔNG TIN CÁ NHÂN</h2>
            <div class="profile-info">
                <img src="https://static.vecteezy.com/system/resources/thumbnails/002/387/693/small_2x/user-profile-icon-free-vector.jpg" alt="Profile Picture">
                <div>
                    <!-- Hiển thị thông tin người dùng -->
                    <?php if (isset($_SESSION['user'])): ?>
                        <p><strong>Họ và tên:</strong> <?= $_SESSION['user']['TenTV'] ?? 'Không có dữ liệu' ?></p>
                        <p><strong>Email:</strong> <?= $_SESSION['user']['Email'] ?? 'Không có dữ liệu' ?></p>
                        <p><strong>Username:</strong> <?= $_SESSION['user']['TenDangNhap'] ?? 'Không có dữ liệu' ?></p>
                        <p><strong>Địa chỉ:</strong> <?= $_SESSION['user']['Diachi'] ?? 'Không có dữ liệu' ?></p>
                        <p><strong>Số điện thoại:</strong> <?= $_SESSION['user']['SDT'] ?? 'Không có dữ liệu' ?></p>
                        <p><strong>Hạng TV:</strong> <?= $_SESSION['user']['Hang'] ?? 'Không có dữ liệu' ?></p>
                    <?php else: ?>
                        <p>Bạn chưa đăng nhập.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <hr>

    <!-- Phần ĐƠN ĐẶT HÀNG -->
    <div class="cart-section">
        <div class="d-flex align-items-center">
            <i class="fa-solid fa-file-invoice-dollar" style="font-size: 40px;"></i> <!-- Icon hóa đơn -->
            <h2>ĐƠN ĐẶT HÀNG</h2>
        </div>
        <br>

        <div class="card mb-4">
            <div class="card-body" id="orderDetails">
                <?php if (!empty($orderDetails)): ?>
                    <?php foreach ($orderDetails as $item): ?>
                        <div class="row border-bottom mb-2">
                            <div class="col-8 product-item"> <!-- Định dạng style -->
                                <img src="./assets/imgs/products/<?= htmlspecialchars($item['Hinhanh']) ?>" alt="<?= htmlspecialchars($item['TenSP']) ?>" class="img-thumbnail product-image">
                                <div>
                                    <strong><?= htmlspecialchars($item['TenSP']) ?></strong><br>
                                    <small>Mã SP: <?= htmlspecialchars($item['MaSP']) ?></small>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                SL: <?= htmlspecialchars($item['SoLuong']) ?> x <?= number_format($item['ThanhTien']) ?> đ
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Giỏ hàng trống.</p>
                <?php endif; ?>
                <div class="card-footer">
                    <span class="total-text">Tổng đơn đặt hàng:</span>&nbsp;
                    <span id="totalAmount" class="text-end"><?= number_format($total) ?> đ</span>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <!-- Hình thức giao hàng -->
            <div class="card-body">
                <h5>Hình thức giao hàng:</h5>
                <div class="form-check">
                    <input class="form-check-input shipping-option" type="radio" name="shipping" id="fastShipping" value="30000">
                    <label class="form-check-label" for="fastShipping">Giao hàng nhanh (30.000 đ)</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input shipping-option" type="radio" name="shipping" id="ecoShipping" value="0" checked>
                    <label class="form-check-label" for="ecoShipping">Giao hàng tiết kiệm (Miễn phí)</label>
                </div>

                <h5 class="mt-4">Hình thức thanh toán:</h5>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="payment" id="bankPayment" value="bank">
                    <label class="form-check-label" for="bankPayment">Ngân hàng điện tử</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="payment" id="momoPayment" value="momo">
                    <label class="form-check-label" for="momoPayment">Momo</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="payment" id="codPayment" value="cod" checked>
                    <label class="form-check-label" for="codPayment">Thanh toán khi nhận hàng</label>
                </div>
            </div>

            <!-- Nút thanh toán -->
            <div class="card-footer">
                <span class="total-text">Tổng số tiền phải thanh toán:</span>
                <span id="totalPayment" class="total-amount"><?= number_format($totalPayment) ?> đ</span>
                <?php
                // Truy vấn giỏ hàng hiện tại của người dùng
                $cartResult = mysqli_query($conn, "SELECT * FROM giohang WHERE MaTV = '$userID' AND TinhTrang = 1 LIMIT 1");
                $cartRow = mysqli_fetch_assoc($cartResult);

                if (!$cartRow) {
                    echo "Giỏ hàng của bạn không có sản phẩm nào.";
                    exit();
                }

                $cartID = $cartRow['MaGH']; // Lấy mã giỏ hàng

                // Kiểm tra nếu biểu mẫu được gửi
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Đảm bảo $cartID được thiết lập và hợp lệ
                    if (isset($cartID) && !empty($cartID)) {
                        // Cập nhật TinhTrang giỏ hàng từ 1 thành 0 cho giỏ hàng cụ thể
                        $updateCartStatus = mysqli_query($conn, "UPDATE giohang SET TinhTrang = 0 WHERE MaGH = '$cartID' AND TinhTrang = 1");

                        // Kiểm tra nếu truy vấn thành công
                        if ($updateCartStatus) {
                            $updateCarDetail  = mysqli_query($conn, "DELETE FROM chitietgiohang WHERE MaGH = '$cartID'");
                        } else {
                            // Xuất thông báo lỗi
                            echo "Lỗi khi cập nhật trạng thái giỏ hàng: " . mysqli_error($conn);
                        }
                    } else {
                        echo "Mã giỏ hàng không hợp lệ.";
                    }
                }
                ?>
                <form method="POST" class="payment-form" onsubmit="return showSuccessMessage(event);">
                    <button type="submit" class="btn btn-light">Thanh toán</button>
                </form>

                <!-- Lớp phủ để làm mờ nền -->
                <div id="overlay"></div>

                <!-- Thông báo thành công -->
                <div id="successMessage">
                    Đặt hàng thành công!
                </div>
            </div>

            <!-- Footer -->
            <?php include 'layouts/footer.php'; ?>

            <!-- Scripts -->
            <script src="scripts/header.js"></script>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
            </script>

            <script>
                // Lấy tất cả các tùy chọn vận chuyển
                const shippingOptions = document.querySelectorAll('.shipping-option');
                // Lấy phần tử hiển thị tổng số tiền
                const totalAmountElement = document.getElementById('totalAmount');
                // Lấy phần tử hiển thị tổng số tiền phải thanh toán
                const totalPaymentElement = document.getElementById('totalPayment');

                // Thêm sự kiện lắng nghe cho mỗi tùy chọn vận chuyển khi thay đổi
                shippingOptions.forEach(option => {
                    option.addEventListener('change', updateTotal);
                });

                // Hàm cập nhật tổng số tiền phải thanh toán
                function updateTotal() {
                    // Lấy chi phí vận chuyển từ tùy chọn đã chọn
                    const shippingCost = parseInt(document.querySelector('input[name="shipping"]:checked').value);
                    // Lấy tổng số tiền hiện tại từ phần tử hiển thị và loại bỏ dấu phẩy
                    const total = parseInt(totalAmountElement.innerText.replace(/,/g, ''));

                    // Tính toán tổng số tiền thanh toán
                    const totalPayment = total + shippingCost;

                    // Cập nhật hiển thị tổng số tiền phải thanh toán với định dạng số
                    totalPaymentElement.innerText = new Intl.NumberFormat().format(totalPayment) + ' đ';
                }

                // Hàm hiển thị thông báo thành công khi gửi form
                function showSuccessMessage(event) {
                    event.preventDefault(); // Ngăn chặn việc gửi form

                    // Hiển thị lớp phủ và thông báo thành công
                    document.getElementById('overlay').style.display = 'block';
                    document.getElementById('successMessage').style.display = 'block';

                    // Tùy chọn: có thể reset form sau khi hiển thị thông báo
                    document.querySelector('.payment-form').reset();

                    // Tự động ẩn thông báo sau vài giây
                    setTimeout(() => {
                        document.getElementById('successMessage').style.display = 'none';
                        document.getElementById('overlay').style.display = 'none';
                    }, 3000); // Điều chỉnh thời gian tính bằng milliseconds

                    // Chuyển hướng người dùng sau khi hiển thị thông báo
                    setTimeout(() => {
                        window.location.href = 'giohang.php';
                    }, 3000); // Điều chỉnh thời gian tính bằng milliseconds
                }
            </script>
</body>

</html>