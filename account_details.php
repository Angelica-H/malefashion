<?php
include 'includes/db_connect.php'; // Kết nối cơ sở dữ liệu
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php"); // Chuyển hướng đến trang đăng nhập
    exit;
}

// Kiểm tra thông báo
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'];

    // Hiển thị thông báo
    echo "<div class='alert alert-$message_type'>$message</div>";

    // Xóa thông báo sau khi hiển thị
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}

// Lấy ID khách hàng từ session và truy vấn dữ liệu
$customer_id = $_SESSION['customer_id'];
$sql = "SELECT * FROM customers WHERE customer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $customer = $result->fetch_assoc(); // Lấy thông tin khách hàng
} else {
    echo "Không tìm thấy thông tin khách hàng.";
    exit;
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Male_Fashion Template">
    <meta name="keywords" content="Male_Fashion, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Male-Fashion | Template</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Css Styles -->
    <?php include "includes/css.php" ?>

</head>

<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Offcanvas Menu Begin -->
    <?php include "includes/menu_begin.php"
        ?>
    <!-- Offcanvas Menu End -->

    <!-- Header Section Begin -->
    <?php include "includes/header_section.php" ?>
    <!-- Header Section End -->

    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>tài khoản</h4>
                        <div class="breadcrumb__links">
                            <a href="./index.html">Home</a>
                            <a href="./shop.html">Shop</a>
                            <span>Thông tin tài khoản</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Checkout Section Begin -->
    <section class="checkout spad">
        <!-- Thông báo-->
        <?php include "includes/notification.php" ?>
        <div class="container">
            <!-- Form Cập Nhật Tài Khoản -->
            <div class="update-account">
                <h2>Cập Nhật Thông Tin Tài Khoản</h2>
                <form id="updateAccountForm" action="login_form/process_update_account.php" method="POST">
                    <div class="mb-3">
                        <label for="first_name" class="form-label">Họ</label>
                        <input type="text" class="form-control" id="first_name" name="first_name"
                            value="<?php echo htmlspecialchars($customer['first_name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Tên</label>
                        <input type="text" class="form-control" id="last_name" name="last_name"
                            value="<?php echo htmlspecialchars($customer['last_name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?php echo htmlspecialchars($customer['email']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number"
                            value="<?php echo htmlspecialchars($customer['phone_number']); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="shipping_address" class="form-label">Địa chỉ giao hàng</label>
                        <textarea class="form-control" id="shipping_address"
                            name="shipping_address"><?php echo htmlspecialchars($customer['shipping_address']); ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Cập Nhật</button>
                </form>
            </div>
            <!-- Nút bấm hiển thị modal thay đổi mật khẩu -->
            <button type="button" class="btn btn-secondary" id="changePasswordButton">Thay đổi mật khẩu</button>

            <!-- Modal thay đổi mật khẩu -->
            <div class="modal" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="changePasswordModalLabel">Thay đổi mật khẩu</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="changePasswordForm" method="POST">
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Mật khẩu hiện tại</label>
                                    <input type="password" class="form-control" id="current_password"
                                        name="current_password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">Mật khẩu mới</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="confirm_new_password" class="form-label">Xác nhận mật khẩu mới</label>
                                    <input type="password" class="form-control" id="confirm_new_password"
                                        name="confirm_new_password" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Thay đổi mật khẩu</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            <script>
                document.getElementById('changePasswordButton').addEventListener('click', function () {
                    var changePasswordModal = new bootstrap.Modal(document.getElementById('changePasswordModal'));
                    changePasswordModal.show();
                });

                document.getElementById('changePasswordForm').addEventListener('submit', function (event) {
                    event.preventDefault(); // Ngăn form tải lại trang
                    var formData = new FormData(this); // Lấy dữ liệu từ form

                    // Gửi yêu cầu thay đổi mật khẩu
                    fetch('login_form/process_change_password.php', {
                        method: 'POST',
                        body: formData
                    })
                        .then(response => response.json())  // Chuyển phản hồi thành JSON
                        .then(data => {
                            if (data.status === 'success') {
                                alert(data.message); // Thông báo thành công
                                var changePasswordModal = bootstrap.Modal.getInstance(document.getElementById('changePasswordModal'));
                                changePasswordModal.hide(); // Đóng modal
                            } else {
                                alert(data.message); // Thông báo lỗi
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });

            </script>

        </div>
    </section>

    <?php include "includes/footer_section.php" ?>

    <div class="search-model">
        <div class="h-100 d-flex align-items-center justify-content-center">
            <div class="search-close-switch">+</div>
            <form class="search-model-form">
                <input type="text" id="search-input" placeholder="Search here.....">
            </form>
        </div>
    </div>
    <!-- Search End -->

    <!-- Js Plugins -->
    <?php include "includes/js.php" ?>

</body>

</html>