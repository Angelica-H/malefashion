<?php
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Kiểm tra token có hợp lệ không
    $stmt = $pdo->prepare("SELECT email, created_at FROM password_resets WHERE token = :token");
    $stmt->execute(['token' => $token]);
    $result = $stmt->fetch();
    
    if ($result) {
        $created_at = new DateTime($result['created_at']);
        $now = new DateTime();
        $interval = $created_at->diff($now);
        
        if ($interval->i >= 60) { // Kiểm tra nếu đã qua 60 phút
            error_log("Token đã hết hạn: " . $token);
            header("Location: ../reset-password.php?error=token_expired&token=" . urlencode($token));
            exit();
        }
        
        if ($new_password !== $confirm_password) {
            header("Location: ../reset-password.php?error=password_mismatch&token=" . urlencode($token));
            exit();
        }
        
        $email = $result['email'];
        
        // Cập nhật mật khẩu mới
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE admin SET password = :password WHERE email = :email");
        $stmt->execute([
            'password' => $hashed_password,
            'email' => $email
        ]);
        
        // Xóa token đã sử dụng
        $stmt = $pdo->prepare("DELETE FROM password_resets WHERE email = :email");
        $stmt->execute(['email' => $email]);
        
        // Xóa hết session
        session_unset();
        session_destroy();
        
        // Chuyển hướng về trang login với thông báo thành công
        header("Location: ../login.php?success=password_reset");
        exit();
    } else {
        // Thêm debug để kiểm tra token
        error_log("Token không tồn tại: " . $token);
        header("Location: ../reset-password.php?error=invalid_token");
        exit();
    }
} else {
    header("Location: ../recover-password.php");
    exit();
}
