<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require '../includes/db_connect.php'; // Kết nối cơ sở dữ liệu

    // Nhận thông tin từ form
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone_number = trim($_POST['phone_number']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Kiểm tra nếu mật khẩu và xác nhận mật khẩu khớp nhau
    if ($password !== $confirm_password) {
        die('Mật khẩu và xác nhận mật khẩu không khớp!');
    }

    // Mã hóa mật khẩu
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Chuẩn bị truy vấn SQL
    $sql = "INSERT INTO customers (first_name, last_name, email, phone_number, password) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $first_name, $last_name, $email, $phone_number, $hashed_password);

    // Thực thi truy vấn và kiểm tra kết quả
    if ($stmt->execute()) {
        echo "Đăng ký thành công!";
        header('Location: ../login.php'); // Chuyển hướng đến trang đăng nhập
    } else {
        echo "Lỗi: " . $stmt->error;
    }

    // Đóng kết nối
    $stmt->close();
    $conn->close();
}
?>
