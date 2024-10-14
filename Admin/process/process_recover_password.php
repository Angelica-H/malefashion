<?php
require 'config.php';
require '../includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    
    // Kiểm tra email có tồn tại trong bảng admin không
    $stmt = $pdo->prepare("SELECT admin_id FROM admin WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $admin = $stmt->fetch();
    
    if ($admin) {
        // Tạo token
        $token = bin2hex(random_bytes(32));
        $expires = date("Y-m-d H:i:s", strtotime('+1 hour'));
        
        // Kiểm tra xem email đã tồn tại trong bảng password_resets chưa
        $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $existing_reset = $stmt->fetch();
        
        if ($existing_reset) {
            // Nếu đã tồn tại, cập nhật token và thời gian
            $stmt = $pdo->prepare("UPDATE password_resets SET token = :token, created_at = :created_at WHERE email = :email");
        } else {
            // Nếu chưa tồn tại, chèn bản ghi mới
            $stmt = $pdo->prepare("INSERT INTO password_resets (email, token, created_at) VALUES (:email, :token, :created_at)");
        }
        
        $stmt->execute([
            'email' => $email,
            'token' => $token,
            'created_at' => date("Y-m-d H:i:s")
        ]);
        
        // Hiển thị token cho admin (thay vì gửi email)
        ?>
        <!DOCTYPE html>
        <html lang="vi">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Mã đặt lại mật khẩu</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body>
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card shadow">
                            <div class="card-body">
                                <h4 class="card-title text-center mb-4">Mã đặt lại mật khẩu</h4>
                                <p class="text-center">Mã đặt lại mật khẩu của bạn là:</p>
                                <div class="alert alert-info text-center" role="alert"><strong><?php echo $token; ?></strong></div>
                                <p class="text-center">Vui lòng sử dụng mã này để đặt lại mật khẩu trong vòng một giờ tới.</p>
                                <div class="d-grid gap-2">
                                    <a href="../reset-password.php" class="btn btn-primary">Đặt lại mật khẩu</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        </body>
        </html>
        <?php
    } else {
        // Redirect back to recover-password.php with an error message
        header("Location: ../recover-password.php?error=1");
        exit();
    }
} else {
    header("Location: ../recover-password.php");
    exit();
}
