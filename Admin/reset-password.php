<?php
require_once 'process/config.php';
require_once 'includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Xử lý đặt lại mật khẩu
    // ...
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt Lại Mật Khẩu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Đặt Lại Mật Khẩu</h2>
                        <form action="process/process_reset_password.php" method="POST">
                            <div class="mb-3">
                                <input type="text" class="form-control" name="token" required placeholder="Nhập mã đặt lại mật khẩu của bạn">
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control" name="new_password" required placeholder="Nhập mật khẩu mới">
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control" name="confirm_password" required placeholder="Xác nhận mật khẩu mới">
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Đặt Lại Mật Khẩu</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
