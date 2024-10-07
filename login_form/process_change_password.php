<?php
session_start();
include '../includes/db_connect.php'; // Kết nối cơ sở dữ liệu

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = $_SESSION['customer_id']; // Lấy ID khách hàng từ session
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];

    // Kiểm tra mật khẩu mới và xác nhận mật khẩu mới
    if ($new_password !== $confirm_new_password) {
        echo json_encode(['status' => 'error', 'message' => 'Mật khẩu mới và xác nhận mật khẩu không khớp.']);
        exit;
    }

    // Truy vấn mật khẩu hiện tại từ cơ sở dữ liệu
    $sql = "SELECT password FROM customers WHERE customer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $customer = $result->fetch_assoc();

        // Kiểm tra mật khẩu hiện tại
        if (password_verify($current_password, $customer['password'])) {
            // Cập nhật mật khẩu mới
            $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $sql = "UPDATE customers SET password = ? WHERE customer_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $new_password_hashed, $customer_id);

            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Mật khẩu đã được thay đổi thành công.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Có lỗi xảy ra khi cập nhật mật khẩu.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Mật khẩu hiện tại không chính xác.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy thông tin khách hàng.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Yêu cầu không hợp lệ.']);
}
