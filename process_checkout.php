<?php
session_start();

// Kết nối cơ sở dữ liệu
include './includes/db_connect.php';

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['shipping_address'])) {
    $_SESSION['error_message'] = "Vui lòng cập nhật địa chỉ giao hàng.";
    header("Location: account_details.php");
    exit();
}

// Lấy dữ liệu giỏ hàng từ localStorage
$cart = json_decode($_POST['cart_data'], true);

// Kiểm tra xem giỏ hàng có items không
if (empty($cart)) {
    $_SESSION['error_message'] = "Giỏ hàng của bạn đang trống. Vui lòng thêm sản phẩm trước khi đặt hàng.";
    header("Location: shopping-cart.php");
    exit();
}

// Lưu đơn hàng vào bảng orders
$customerId = $_SESSION['customer_id'];
$totalAmount = $_POST['total_amount'];
$shippingAddress = $_SESSION['shipping_address'];
$paymentMethod = "Khi nhận hàng";

// Lưu đơn hàng vào bảng orders
$sql = "INSERT INTO orders (customer_id, total, shipping_address, payment_method) VALUES ('$customerId', '$totalAmount', '$shippingAddress', '$paymentMethod')";
if ($conn->query($sql) === TRUE) {
    $orderId = $conn->insert_id;
    foreach ($cart as $product) {
        // Thêm vào bảng order_items
        $quantity = $product['quantity'];
        $price = $product['price'];
        $sizeName = $product['size'];
        $colorName = $product['color'];
        $conn->query("INSERT INTO order_items (order_id, quantity, price, size, color) 
            VALUES ('$orderId', '$quantity', '$price', '$sizeName', '$colorName')");
    }

    // Lưu thông báo vào session
    $_SESSION['message'] = "Bạn đã đặt hàng thành công. Chúng tôi sẽ liên hệ với bạn sớm nhất để xác nhận đơn hàng!";

    echo "<script>
        localStorage.removeItem('cart'); // Xóa dữ liệu giỏ hàng từ localStorage
        window.location.href = 'shopping-cart.php'; // Chuyển hướng đến trang giỏ hàng
    </script>";
} else {
    echo "Lỗi: " . $conn->error;
}

$conn->close();
?>