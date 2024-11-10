<?php
include "database/conn.php";
session_start();


//* Nếu chưa đăng nhập -> Chuyển tới trang Login
if (!isset($_SESSION['mySession'])) {
    header('location:login.php');
    exit();
}


//* User hiện tại
$userID = $_SESSION['user']['MaTV'];


//* Kiểm tra nếu user đã đăng nhập và có ID
if (isset($userID)) {
    // Truy vấn giỏ hàng hiện tại của người dùng
    $stmt = $conn->prepare("SELECT MaGH FROM giohang WHERE MaTV = ? ORDER BY MaGH ASC LIMIT 1");
    $stmt->bind_param("s", $userID);
    $stmt->execute();
    $stmt->store_result();


    if ($stmt->num_rows > 0) {
        // Người dùng đã có giỏ hàng, lấy mã giỏ hàng
        $stmt->bind_result($cartID);
        $stmt->fetch();
    } else {
        // Người dùng chưa có giỏ hàng, tạo giỏ hàng mới
        $str = rand();
        $MaGH = "GH" . md5($str);
        $insertCartQuery = "
                INSERT INTO giohang (MaGH, MaTV, Ngaytao, Tong, TinhTrang)
                VALUES ('$MaGH','$userID', NOW(), 0, '0')";
        // Thực thi câu lệnh thêm giỏ hàng
        if (mysqli_query($conn, $insertCartQuery)) {
            // Lấy mã giỏ hàng mới vừa được tạo
            $getCartIDQuery = "SELECT MaGH FROM giohang WHERE MaTV = '$userID' ORDER BY MaGH ASC LIMIT 1";
            $getCartIDResult = mysqli_query($conn, $getCartIDQuery);
            $cartID = mysqli_fetch_assoc($getCartIDResult)['MaGH'];
        } else {
            // Xử lý lỗi nếu không thể tạo giỏ hàng
            echo "Lỗi khi tạo giỏ hàng: " . mysqli_error($conn);
        }
    }


    $stmt->close();
} else {
    // Nếu người dùng chưa đăng nhập, chuyển hướng đến trang đăng nhập
    echo "Vui lòng đăng nhập để tạo giỏ hàng.";
}


//* Khởi tạo biến tổng giá trị giỏ hàng
$totalPrice = 0;


//* Thêm sản phẩm vào giỏ hàng
if (isset($_POST['addtocartbtn']) && $_POST['addtocartbtn']) {
    // Lấy thông tin sản phẩm từ form
    $MaSP = $_POST['MaSP'];
    $soluong = $_POST['soluong'];


    // Lấy thông tin sản phẩm từ bảng sanpham
    $productQuery = "SELECT * FROM sanpham WHERE MaSP = '$MaSP'";
    $productResult = mysqli_query($conn, $productQuery);
    $product = mysqli_fetch_assoc($productResult);


    $giaban = $product['Giaban']; // Giá bán
    $giakm = $product['GiaKM'] ? $product['GiaKM'] : $giaban; // Nếu có giá khuyến mãi thì dùng giá khuyến mãi, ngược lại dùng giá bán
    $thanhtien = $soluong * $giakm; // Tính thành tiền cho sản phẩm


    // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
    $checkQuery = "SELECT * FROM chitietgiohang WHERE MaGH = '$cartID' AND MaSP = '$MaSP'";
    $checkResult = mysqli_query($conn, $checkQuery);


    if (mysqli_num_rows($checkResult) > 0) {
        // Nếu sản phẩm đã có trong giỏ hàng, cập nhật số lượng và thành tiền
        $row = mysqli_fetch_assoc($checkResult);
        $newQuantity = $row['SoLuong'] + $soluong; // Tăng số lượng
        $newThanhtien = $newQuantity * $giakm; // Tính lại thành tiền


        // Cập nhật sản phẩm trong giỏ hàng
        $updateQuery = "UPDATE chitietgiohang SET SoLuong = '$newQuantity', Thanhtien = '$newThanhtien' WHERE MaCTGH = '{$row['MaCTGH']}'";
        mysqli_query($conn, $updateQuery);
    } else {
        // Nếu sản phẩm chưa có trong giỏ hàng, thêm mới sản phẩm
        $str = rand(); // Tạo một số ngẫu nhiên
        $MaCTGH = "CG" . md5($str); // Tạo mã chi tiết giỏ hàng
        $insertQuery = "INSERT INTO chitietgiohang (MaCTGH, MaGH, MaSP, SoLuong, Thanhtien) VALUES ('$MaCTGH', '$cartID', '$MaSP', '$soluong', '$thanhtien')";
        mysqli_query($conn, $insertQuery); // Thực hiện thêm sản phẩm vào giỏ hàng
    }
}


//* Xóa sản phẩm khỏi giỏ hàng
if (isset($_POST['remove']) && isset($_POST['MaSP'])) {
    // Lấy mã sản phẩm cần xóa
    $MaSPToRemove = $_POST['MaSP'];


    // Lấy thành tiền của sản phẩm hiện tại để trừ khỏi tổng giỏ hàng
    $currentItemQuery = "SELECT Thanhtien FROM chitietgiohang WHERE MaGH = '$cartID' AND MaSP = '$MaSPToRemove'";
    $currentItemResult = mysqli_query($conn, $currentItemQuery);
    $currentItem = mysqli_fetch_assoc($currentItemResult);


    // Lấy thành tiền sản phẩm hiện tại
    $currentItemTotal = $currentItem['Thanhtien'];


    // Xóa sản phẩm khỏi bảng chitietgiohang
    $deleteQuery = "DELETE FROM chitietgiohang WHERE MaGH = '$cartID' AND MaSP = '$MaSPToRemove'";
    mysqli_query($conn, $deleteQuery); // Thực hiện xóa sản phẩm


    // Cập nhật tổng giá trị trong giohang
    $updateTotalQuery = "UPDATE giohang SET Tong = Tong - '$currentItemTotal' WHERE MaGH = '$cartID'";
    mysqli_query($conn, $updateTotalQuery);
}


//* Lấy các sản phẩm trong giỏ hàng để hiển thị và tính tổng giá trị
$cartDetailsResult = mysqli_query($conn, "SELECT chitietgiohang.*, sanpham.TenSP, sanpham.Hinhanh, sanpham.Giaban, sanpham.GiaKM
                                        FROM chitietgiohang
                                        JOIN sanpham ON chitietgiohang.MaSP = sanpham.MaSP
                                        WHERE chitietgiohang.MaGH = '$cartID'
                                        ORDER BY chitietgiohang.MaCTGH ASC");


//* Tính tổng giá trị
while ($cartDetailsRow = mysqli_fetch_assoc($cartDetailsResult)) {
    if ($cartDetailsRow['GiaKM'] != NULL) {
        $totalPrice += $cartDetailsRow['GiaKM'] * $cartDetailsRow['SoLuong']; // Nếu có giá khuyến mãi, sử dụng giá khuyến mãi
    } else {
        $totalPrice += $cartDetailsRow['Giaban'] * $cartDetailsRow['SoLuong']; // Ngược lại, sử dụng giá bán
    }
}


//* Cập nhật tổng giá trị trong bảng giohang sau khi đã tính toán
$updateCartTotalQuery = "UPDATE giohang SET Tong = '$totalPrice' WHERE MaGH = '$cartID'";
mysqli_query($conn, $updateCartTotalQuery);


//* Cập nhật trạng thái TinhTrang dựa trên tổng giá trị
if ($totalPrice > 0) {
    // Nếu giỏ hàng có sản phẩm, đặt TinhTrang = 1
    $updateStatusQuery = "UPDATE giohang SET TinhTrang = 1 WHERE MaGH = '$cartID'";
} else {
    // Nếu giỏ hàng rỗng, đặt TinhTrang = 0
    $updateStatusQuery = "UPDATE giohang SET TinhTrang = 0 WHERE MaGH = '$cartID'";
}
mysqli_query($conn, $updateStatusQuery); // Thực hiện cập nhật trạng thái giỏ hàng


//* Cập nhật số lượng sản phẩm trong giỏ hàng
if (isset($_POST['MaSP']) && isset($_POST['soluong'])) {
    // Lấy thông tin sản phẩm từ form
    $MaSP = $_POST['MaSP'];
    $soluong = (int)$_POST['soluong'];


    // Lấy thông tin sản phẩm từ bảng sanpham
    $productQuery = "SELECT Giaban, GiaKM FROM sanpham WHERE MaSP = '$MaSP'";
    $productResult = mysqli_query($conn, $productQuery);
    $product = mysqli_fetch_assoc($productResult);


    $giaban = $product['Giaban']; // Giá bán
    $giakm = $product['GiaKM'] ? $product['GiaKM'] : $giaban; // Sử dụng giá khuyến mãi nếu có
    $thanhtien = $soluong * $giakm; // Tính lại thành tiền


    // Cập nhật số lượng và thành tiền của sản phẩm trong giỏ hàng
    $updateQuery = "UPDATE chitietgiohang SET SoLuong = '$soluong', Thanhtien = '$thanhtien' WHERE MaGH = '$cartID' AND MaSP = '$MaSP'";
    mysqli_query($conn, $updateQuery); // Thực hiện cập nhật


    // Sau khi cập nhật số lượng, tính toán lại tổng giá trị giỏ hàng
    $totalPrice = 0; // Đặt lại tổng giá trị giỏ hàng
    $cartDetailsResult = mysqli_query($conn, "SELECT Thanhtien FROM chitietgiohang WHERE MaGH = '$cartID'");
    while ($cartDetailsRow = mysqli_fetch_assoc($cartDetailsResult)) {
        $totalPrice += $cartDetailsRow['Thanhtien']; // Cộng tổng giá trị mới
    }


    // Cập nhật tổng giá trị giỏ hàng trong bảng giohang
    $updateTotalQuery = "UPDATE giohang SET Tong = '$totalPrice' WHERE MaGH = '$cartID'";
    mysqli_query($conn, $updateTotalQuery);


    // Sau khi cập nhật, chuyển hướng lại giỏ hàng
    header('Location: giohang.php');
    exit();
}


//Thông báo đặt hàng thành công
if (isset($_SESSION['success_message'])) {
    echo "<div class='alert alert-success'>" . $_SESSION['success_message'] . "</div>";
    unset($_SESSION['success_message']); // Xóa thông báo sau khi hiển thị
}
?>


<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng</title>


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


    <link rel="stylesheet" href="css/giohang.css" />
</head>


<body>
    <!-- Header -->
    <?php include 'layouts/header.php'; ?>


    <div class="container ">
        <!-- Phần thông tin người dùng -->
        <div class="profile-section">
            <h2>THÔNG TIN CÁ NHÂN</h2>
            <div class="profile-info">
                <img src="https://static.vecteezy.com/system/resources/thumbnails/002/387/693/small_2x/user-profile-icon-free-vector.jpg" alt="Profile Picture"> <!-- Hình đại diện người dùng -->
                <div>
                    <!-- Hiển thị thông tin người dùng -->
                    <?php if (isset($_SESSION['user'])): ?>
                        <p><strong>Họ và tên:</strong> <?= $_SESSION['user']['TenTV'] ?? 'Không có dữ liệu' ?></p> <!-- Tên người dùng -->
                        <p><strong>Email:</strong> <?= $_SESSION['user']['Email'] ?? 'Không có dữ liệu' ?></p> <!-- Địa chỉ email -->
                        <p><strong>Username:</strong> <?= $_SESSION['user']['TenDangNhap'] ?? 'Không có dữ liệu' ?></p> <!-- Tên đăng nhập -->
                        <p><strong>Địa chỉ:</strong> <?= $_SESSION['user']['Diachi'] ?? 'Không có dữ liệu' ?></p> <!-- Địa chỉ người dùng -->
                        <p><strong>Số điện thoại:</strong> <?= $_SESSION['user']['SDT'] ?? 'Không có dữ liệu' ?></p> <!-- Số điện thoại -->
                        <p><strong>Hạng TV:</strong> <?= $_SESSION['user']['Hang'] ?? 'Không có dữ liệu' ?></p> <!-- Hạng thành viên -->
                    <?php else: ?>
                        <p>Bạn chưa đăng nhập.</p> <!-- Thông báo nếu người dùng chưa đăng nhập -->
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <hr>


    <!-- Phần giỏ hàng -->
    <div class="cart-section">
        <div class="d-flex align-items-center">
            <i class="fa-solid fa-cart-shopping"></i> <!-- Icon giỏ hàng -->
            <h2>GIỎ HÀNG</h2>
        </div>
        <br>


        <?php if ($totalPrice > 0) { ?> <!-- Kiểm tra xem giỏ hàng có sản phẩm không -->
            <?php
            // Lấy chi tiết giỏ hàng để hiển thị các sản phẩm
            $cartDetailsResult = mysqli_query($conn, "SELECT chitietgiohang.*, sanpham.TenSP, sanpham.Hinhanh, sanpham.Giaban, sanpham.GiaKM
                                                FROM chitietgiohang
                                                JOIN sanpham ON chitietgiohang.MaSP = sanpham.MaSP
                                                WHERE chitietgiohang.MaGH = '$cartID'
                                                ORDER BY chitietgiohang.MaCTGH ASC");
            ?>
            <?php while ($cartDetailsRow = mysqli_fetch_assoc($cartDetailsResult)) { ?> <!-- Lặp qua các sản phẩm trong giỏ hàng -->
                <div class="cart-item">
                    <!-- Hình ảnh sản phẩm -->
                    <img src="./assets/imgs/products/<?= htmlspecialchars($cartDetailsRow['Hinhanh']) ?>" alt="<?= htmlspecialchars($cartDetailsRow['TenSP']) ?>">


                    <!-- Thông tin sản phẩm -->
                    <div class="cart-item-details">
                        <!-- Tên sản phẩm -->
                        <p><strong><?= htmlspecialchars($cartDetailsRow['TenSP']) ?></strong></p>


                        <!-- Mã sản phẩm -->
                        <p>Mã SP: <?= htmlspecialchars($cartDetailsRow['MaSP']) ?></p>


                        <!-- Kiểm tra xem có giá khuyến mãi không -->
                        <p>Chương trình khuyến mãi:
                            <?php if ($cartDetailsRow['GiaKM'] != NULL) { ?>
                                <!-- Hiển thị thông báo khuyến mãi với màu đỏ -->
                                <span style="color:  #ff4b4b; font-weight: bold">Flash Sale - Giảm giá</span>
                            <?php } else { ?>
                                <!-- Thông báo không có khuyến mãi với màu đỏ -->
                                <span style="color:  #ff4b4b; font-weight: bold">Không có khuyến mãi</span>
                            <?php } ?>
                        </p>
                    </div>


                    <div class="cart-item-price">
                        <?php if ($cartDetailsRow['GiaKM'] != NULL) { ?> <!-- Kiểm tra xem có giá khuyến mãi không -->
                            <!-- Giá gốc -->
                            <h5 class="text-secondary text-decoration-line-through"><?= number_format($cartDetailsRow['Giaban'], 0, ",", ".") ?><sup>đ</sup></h5>
                        <?php } ?>
                        <h4 class="text-danger">
                            <!-- Giá hiện tại (có thể là giá khuyến mãi hoặc giá gốc) -->
                            <?= number_format($cartDetailsRow['GiaKM'] ? $cartDetailsRow['GiaKM'] : $cartDetailsRow['Giaban'], 0, ",", ".") ?><sup>đ</sup>
                        </h4>
                    </div>


                    <form action="giohang.php" method="post">
                        <!-- Hiển thị số lượng -->
                        <div class="number form-group mb-3">
                            <label><strong>Số lượng:</strong></label>
                            <input type="number" class="input-sm rounded update-input"
                                name="soluong"
                                value="<?= htmlspecialchars($cartDetailsRow['SoLuong']) ?>"
                                min="1"
                                onchange="updateQuantity(this.value, '<?= $cartDetailsRow['MaSP'] ?>')" />
                        </div>


                        <!-- Hidden input để gửi mã sản phẩm -->
                        <input type="hidden" name="MaSP" value="<?= htmlspecialchars($cartDetailsRow['MaSP']) ?>">


                        <!-- Nút cập nhật -->
                        <!-- <button type="submit" class="btn btn-primary update-btn" name="updateCart">Cập nhật</button> -->
                        <div class="cart-item-remove">
                            <form method="POST" action="">
                                <input type="hidden" name="MaSP" value="<?= htmlspecialchars($cartDetailsRow['MaSP']) ?>"> <!-- Lưu mã sản phẩm để xóa -->
                                <button type="submit" class="btn btn-danger remove-btn" name="remove">Xóa</button> <!-- Nút xóa sản phẩm -->
                            </form>
                        </div>
                    </form>
                </div>
            <?php } ?>


            <br>


            <!-- Tổng tiền -->
            <div class="cart-summary d-flex justify-content-between align-items-center">
                <div class="total-price-container d-flex align-items-center">
                    <!-- Tổng giá trị giỏ hàng -->
                    <p class="total-price">Tổng: <?= number_format($totalPrice, 0, ",", ".") ?>đ</p>


                    <!-- Form để thanh toán -->
                    <form method="POST" action="Hoadon.php">
                        <input type="hidden" name="cart" value=''>
                        <button type="submit" class="checkout-btn">Mua</button> <!-- Nút thanh toán -->
                    </form>
                </div>
            </div>


        <?php } else { ?>
            <p>Giỏ hàng của bạn trống.</p> <!-- Thông báo nếu giỏ hàng trống -->
        <?php } ?>
    </div>


    <!-- Footer -->
    <?php include 'layouts/footer.php'; ?>


    <!-- Scripts -->
    <script src="scripts/header.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>




    <script>
        // Đợi sau 30 giây để ẩn thông báo
        setTimeout(function() {
            // Thêm class 'hidden' vào alert để tạo hiệu ứng mờ dần
            document.querySelector('.alert').classList.add('hidden');


            // Sau khi hiệu ứng hoàn tất (1 giây), xóa alert khỏi DOM
            setTimeout(function() {
                document.querySelector('.alert').remove();
            }, 1000); // Đợi 1 giây để hiệu ứng hoàn tất
        }, 3000); // 3 giây
    </script>


    <script>
        function updateQuantity(newQuantity, productID) {
            // Đảm bảo số lượng lớn hơn 0
            if (newQuantity < 1) {
                alert("Số lượng phải lớn hơn 0");
                return;
            }


            // Gửi yêu cầu cập nhật số lượng sản phẩm
            $.ajax({
                type: "POST",
                url: "giohang.php",
                data: {
                    MaSP: productID,
                    soluong: newQuantity
                },
                success: function(response) {
                    location.reload();
                },
                error: function() {
                    alert("Lỗi khi cập nhật số lượng.");
                }
            });
        }
    </script>


</body>


</html>

