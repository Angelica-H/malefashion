<?php
require 'config.php';
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    
    // Kiểm tra email có tồn tại trong database không
    $stmt = $pdo->prepare("SELECT admin_id FROM admin WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $result = $stmt->fetch();
    
    if ($result) {
        // Tạo token khôi phục
        $token = bin2hex(random_bytes(32));
        $expires = date("Y-m-d H:i:s", strtotime('+1 hour'));
        
        // Lưu token vào database
        $stmt = $pdo->prepare("INSERT INTO password_resets (email, token, expires) VALUES (:email, :token, :expires)");
        $stmt->execute([
            'email' => $email,
            'token' => $token,
            'expires' => $expires
        ]);
        
        // Gửi email khôi phục mật khẩu
        $reset_link = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/reset-password.php?token=" . $token;
        $to = $email;
        $subject = "Password Recovery";
        $message = "Click the following link to reset your password: " . $reset_link;
        $headers = "From: noreply@yourdomain.com";
        
        if(mail($to, $subject, $message, $headers)) {
            echo "<script>alert('An email with password recovery instructions has been sent to your email address.'); window.location.href='../recover-password.php';</script>";
        } else {
            echo "<script>alert('Failed to send recovery email. Please try again later.'); window.location.href='../recover-password.php';</script>";
        }
    } else {
        echo "<script>alert('No account found with that email address.'); window.location.href='../recover-password.php';</script>";
    }
} else {
    header("Location: ../recover-password.php");
    exit();
}
