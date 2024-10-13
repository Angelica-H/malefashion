<?php
session_start();
require 'config.php'; // Kết nối đến cơ sở dữ liệu

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Kiểm tra email có đúng định dạng không
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.'); window.location.href='login.php';</script>";
        exit();
    }

    // Kiểm tra thông tin người dùng trong cơ sở dữ liệu
    try {
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Đăng nhập thành công
            $_SESSION['user_id'] = $user['admin_id']; // Thay đổi thành admin_id
            $_SESSION['role'] = $user['role']; // Thêm role nếu cần thiết
            header("Location: ../index.php"); // Chuyển hướng đến trang dashboard
            exit();
        } else {
            // Đăng nhập thất bại
            echo "<script>alert('Invalid email or password.'); window.location.href='../login.php';</script>";
        }
    } catch (PDOException $e) {
        // Xử lý lỗi kết nối cơ sở dữ liệu và ghi vào log
        error_log($e->getMessage(), 3, 'errors.log'); 
        echo "<script>alert('A system error occurred. Please try again later.'); window.location.href='../login.php';</script>";
    }
}
?>
