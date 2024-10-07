<?php
session_start();
include '../includes/db_connect.php'; // Kết nối cơ sở dữ liệu

header('Content-Type: application/json'); // Đặt header trả về JSON

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Truy vấn kiểm tra thông tin đăng nhập
    $sql = "SELECT * FROM customers WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Kiểm tra nếu có kết quả trả về
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Kiểm tra mật khẩu
        if (password_verify($password, $user['password'])) {
            // Lưu thông tin đăng nhập vào session
            $_SESSION['loggedin'] = true; // Đánh dấu người dùng đã đăng nhập
            $_SESSION['customer_id'] = $user['customer_id']; // Lưu ID khách hàng
            $_SESSION['first_name'] = $user['first_name']; // Lưu tên
            $_SESSION['last_name'] = $user['last_name']; // Lưu họ
            $_SESSION['email'] = $user['email']; // Lưu email
            $_SESSION['phone_number'] = $user['phone_number']; // Lưu sdt
            $_SESSION['shipping_address'] = $user['shipping_address']; // Lưu shipping_address
            // Trả về dữ liệu JSON
            echo json_encode([
                'status' => 'success',
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name']
            ]);
            header('Location: ../account_details.php');
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Mật khẩu không chính xác!']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Email không tồn tại!']);
    }

    $stmt->close();
    $conn->close();
}
?>