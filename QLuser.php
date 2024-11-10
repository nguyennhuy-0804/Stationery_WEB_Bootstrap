<?php
session_start(); // Bắt đầu phiên
include "database/conn.php"; // Kết nối đến cơ sở dữ liệu
$message = "";

// Thực hiện cập nhật mật khẩu khi admin gửi form
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_password'])) {
    $userMaTK = $_POST['MaTK'];
    $newPassword = $_POST['new_password'];


    $updatePasswordSql = "UPDATE taikhoan SET MatKhau = '$newPassword' WHERE MaTK = '$userMaTK'";
    if ($conn->query($updatePasswordSql) === TRUE) {
        echo "Đổi mật khẩu thành công cho tài khoản $userMaTK.";
    } else {
        echo "Lỗi: " . $conn->error;
    }
}


// Lấy danh sách người dùng
$sql = "SELECT * FROM taikhoan INNER JOIN thanhvien ON taikhoan.MaTK = thanhvien.MaTK";
$result = $conn->query($sql);
?>







<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Sản Phẩm</title>


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

    <style>
        button:hover {
            background: #218838;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-left: 5px;
            /* Điều chỉnh khoảng cách bên phải */
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background: #f8f9fa;
            text-align: center;
        }

        .message {
            display: block;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        /* CSS cho modal */
        .modal {
            display: none;
            /* Ẩn modal mặc định */
            position: fixed;
            /* Đặt modal ở vị trí cố định */
            z-index: 1000;
            /* Đảm bảo modal nằm trên tất cả các phần tử khác */
            left: 0;
            top: 0;
            width: 100%;
            /* Toàn bộ chiều rộng */
            height: 100%;
            /* Toàn bộ chiều cao */
            overflow: auto;
            /* Bật cuộn nếu cần */
            background-color: rgba(0, 0, 0, 0.4);
            /* Màu nền nửa trong suốt */
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            /* 15% từ trên và giữa theo chiều ngang */
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            /* Chiều rộng của modal */
            max-width: 600px;
            /* Chiều rộng tối đa */
            border-radius: 5px;
            /* Bo góc */
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* Điều chỉnh responsive */
        @media (max-width: 768px) {
            .action-button {
                margin: 5px
            }

            .message {
                display: none;
                /* Ẩn mặc định */
                padding: 15px;
                margin: 10px 0;
                border-radius: 5px;
            }
        }

        /* Thiết lập chung cho bảng */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }


        th,
        td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }


        th {
            background: #f8f9fa;
            text-align: center;
        }


        /* Responsive cho bảng dạng cột */
        @media (max-width: 768px) {

            table,
            thead,
            tbody,
            th,
            td,
            tr {
                display: block;
                /* Hiển thị từng ô bảng dưới dạng khối */
            }


            /* Ẩn tiêu đề của bảng */
            thead tr {
                display: none;
            }


            /* Thiết lập từng dòng dữ liệu thành một khối */
            tr {
                margin-bottom: 15px;
                border-bottom: 1px solid #ddd;
            }


            /* Hiển thị tiêu đề cột bên trong ô dữ liệu */
            td {
                position: relative;
                padding-left: 50%;
                text-align: left;
            }


            /* Gán tên tiêu đề cho mỗi ô dữ liệu bằng data-label */
            td::before {
                content: attr(data-label);
                /* Lấy giá trị từ data-label để làm tiêu đề */
                position: absolute;
                left: 10px;
                font-weight: bold;
                white-space: nowrap;
            }

        }
    </style>


</head>


<body>


    <!-- Header -->
    <?php include 'layouts/AdminHeader.php'; ?>



    <div class="container mt-5" style="display: flex; flex-direction: column; align-items: center; text-align: center;">
        <h1 style="margin-bottom: 30px;">Quản lý tài khoản</h1>
        <!-- Form thêm sản phẩm -->
        <!-- <style>
            .form-container {
                display: flex;
                flex-wrap: wrap;
                /* Cho phép xuống dòng nếu không đủ chỗ */
                gap: 20px;
                /* Tăng khoảng cách giữa các khung (có thể điều chỉnh) */
            }


            input[type="text"],
            input[type="number"],
            input[type="file"],
            textarea,
            input[type="date"] {
                width: calc(100% - 20px);
                /* Đảm bảo các khung chiếm đầy chiều rộng, trừ đi padding */
                padding: 10px;
                /* Khoảng cách bên trong khung */
                box-sizing: border-box;
                /* Đảm bảo padding không làm tăng chiều rộng */
                border: 1px solid #ccc;
                /* Thêm đường viền để nhìn rõ hơn */
                border-radius: 4px;
                /* Bo góc khung */
            }


            textarea {
                height: 100px;
                /* Thiết lập chiều cao cho textarea */
                resize: vertical;
                /* Cho phép thay đổi kích thước theo chiều dọc */
                margin-top: 15px;
                /* Khoảng cách trên cho textarea */
            }


            .checkbox-group {
                display: flex;
                align-items: center;
                /* Căn giữa checkbox và label */
            }


            .checkbox-group input {
                margin-right: 5px;
                /* Khoảng cách giữa checkbox và label */
            }


            .btn {
                margin-top: 10px;
                /* Khoảng cách giữa nút và các khung input */
            }
        </style> -->

        <!-- Hiển thị thông báo -->
        <?php if ($message): ?>
            <div class="message <?php echo strpos($message, 'Lỗi') === false ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>


        <!-- Bảng tài khoản -->
        <div class="table-wrapper">
            <table>
                <tr>
                    <th>Mã TK</th>
                    <th>Tên đăng nhập</th>
                    <th>Email</th>
                    <th>Địa chỉ</th>
                    <th>Số điện thoại</th>
                    <th>Hạng</th>
                    <th>Role</th>
                    <th>Đổi mật khẩu</th>
                </tr>
                <?php
                if ($result->num_rows > 0) {
                    while ($user = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $user['MaTK'] . "</td>";
                        echo "<td>" . $user['TenDangNhap'] . "</td>";
                        echo "<td>" . $user['Email'] . "</td>";
                        echo "<td>" . $user['Diachi'] . "</td>";
                        echo "<td>" . $user['SDT'] . "</td>";
                        echo "<td>" . $user['Hang'] . "</td>";
                        echo "<td>" . ($user['RoleID'] == 0 ? 'Admin' : 'User') . "</td>";
                        echo "<td>";
                        echo "<form method='post' action=''>";
                        echo "<input type='hidden' name='MaTK' value='" . $user['MaTK'] . "'>";
                        echo "<input type='password' name='new_password' placeholder='Mật khẩu mới' required>";
                        echo "<input type='submit' name='update_password' value='Đổi mật khẩu'>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>Không có người dùng nào.</td></tr>";
                }
                ?>
            </table>

        </div>


    </div>


    <!-- <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <form method="post" action="">
                <input type="hidden" name="MaTK" id="MTK">

                <input type="text" name="MatKhau" id="MatKhau" placeholder="Mật khẩu" required>

                <button type="submit" class="btn btn-primary" name="edit_password">Cập nhật</button>
            </form>
        </div>
    </div> -->


    <!-- <script>
        function openModal(MaTK, MatKhau) {
            document.getElementById('myModal').style.display = 'block';
            document.getElementById('MaTK').value = MaTK;
            document.getElementById('MatKhau').value = MatKhau;


        }


        function closeModal() {
            document.getElementById('myModal').style.display = "none"; // Ẩn modal
        }


        // Đóng modal khi nhấn ra ngoài
        window.onclick = function(event) {
            const modal = document.getElementById('myModal');
            if (event.target === modal) {
                closeModal();
            }
        }


        document.addEventListener('DOMContentLoaded', function() {
            const messageDiv = document.querySelector('.message');
            if (messageDiv) {
                setTimeout(() => {
                    messageDiv.style.display = 'none';
                }, 5000); // 5 giây
            }
        });
    </script> -->
    <!-- Footer -->
    <?php include 'layouts/AdminFooter.php'; ?>


    <!-- Scripts -->
    <script src="scripts/header.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>


</body>


</html>


<?php
$conn->close(); // Close the database connection
?>