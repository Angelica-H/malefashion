<?php
session_start();
include '../includes/db_connect.php'; // Kết nối cơ sở dữ liệu

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = $_SESSION['customer_id']; // Lấy ID khách hàng từ session
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $shipping_address = $_POST['shipping_address'];

    // Kiểm tra xem có dữ liệu hay không
    if (empty($first_name) || empty($last_name) || empty($email)) {
        echo json_encode(['status' => 'error', 'message' => 'Vui lòng điền đầy đủ thông tin']);
        exit;
    }

    // Cập nhật thông tin khách hàng
    $sql = "UPDATE customers SET first_name = ?, last_name = ?, email = ?, phone_number = ?, shipping_address = ? WHERE customer_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo json_encode(['status' => 'error', 'message' => 'Không thể chuẩn bị câu lệnh SQL']);
        exit;
    }
    
    $stmt->bind_param("sssssi", $first_name, $last_name, $email, $phone_number, $shipping_address, $customer_id);

    if ($stmt->execute()) {
        $_SESSION['first_name'] = $first_name;
        $_SESSION['last_name'] = $last_name;
        $_SESSION['email'] = $email;
        $_SESSION['shipping_address'] = $shipping_address;
        
        // Lưu thông báo vào session
        $_SESSION['message'] = "Cập nhật thông tin thành công!";
        $_SESSION['message_type'] = "success"; // Hoặc "danger" nếu có lỗi

        echo json_encode(['status' => 'success', 'message' => 'Cập nhật thông tin thành công']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Cập nhật thông tin không thành công']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Yêu cầu không hợp lệ']);
}
$conn->close();
header("Location: ../account_details.php");
exit;
?>
