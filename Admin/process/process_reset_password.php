<?php
require 'config.php';
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($new_password !== $confirm_password) {
        echo "Passwords do not match.";
        exit();
    }
    
    // Kiểm tra token có hợp lệ không
    $stmt = $conn->prepare("SELECT email FROM password_resets WHERE token = ? AND expires > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        echo "Invalid or expired token.";
        exit();
    }
    
    $email = $result->fetch_assoc()['email'];
    
    // Cập nhật mật khẩu mới
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE admin SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $hashed_password, $email);
    $stmt->execute();
    
    // Xóa token đã sử dụng
    $stmt = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    
    echo "Your password has been successfully reset. You can now <a href='" . BASE_URL . "login.php'>login</a> with your new password.";
} else {
    header("Location: " . BASE_URL . "login.php");
    exit();
}

