<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['customer_id'])) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập để tiếp tục thanh toán.']);
    exit;
}

$customer_id = $_SESSION['customer_id'];
$total_amount = $_POST['total_amount'];
$cart_data = json_decode($_POST['cart_data'], true);
$shipping_address = $_POST['shipping_address'];
$phone_number = $_POST['phone_number'];
$payment_method = $_POST['payment_method'];

$conn->begin_transaction();

try {
    $order_query = "INSERT INTO orders (customer_id, order_date, total, status, shipping_address) 
                    VALUES (?, NOW(), ?, 'Pending', ?)";
    $stmt = $conn->prepare($order_query);
    $stmt->bind_param("ids", $customer_id, $total_amount, $shipping_address);
    $stmt->execute();
    $order_id = $conn->insert_id;

    $payment_query = "INSERT INTO payments (order_id, amount, payment_method, status) 
                     VALUES (?, ?, ?, ?)";
    $payment_status = ($payment_method === 'VNPAY') ? 'Pending' : 'Unpaid';
    $stmt = $conn->prepare($payment_query);
    $stmt->bind_param("idss", $order_id, $total_amount, $payment_method, $payment_status);
    $stmt->execute();
    $payment_id = $conn->insert_id;

    $order_item_query = "INSERT INTO order_items (order_id, variant_id, sku_id, quantity, price) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($order_item_query);
    
    $get_product_query = "SELECT pv.variant_id, s.sku_id, s.stock FROM product_variants pv 
                          JOIN sku s ON pv.variant_id = s.variant_id 
                          WHERE pv.product_id = ? AND pv.color_id = ? AND pv.size_id = ?";
    $get_product_stmt = $conn->prepare($get_product_query);
    
    $update_stock_query = "UPDATE sku SET stock = stock - ? WHERE sku_id = ?";
    $update_stock_stmt = $conn->prepare($update_stock_query);

    foreach ($cart_data as $item) {
        $get_product_stmt->bind_param("iii", $item['product_id'], $item['color_id'], $item['size_id']);
        $get_product_stmt->execute();
        $product_result = $get_product_stmt->get_result();
        $product_data = $product_result->fetch_assoc();

        if (!$product_data) {
            throw new Exception("Không tìm thấy sản phẩm hoặc SKU phù hợp.");
        }

        if ($product_data['stock'] < $item['quantity']) {
            throw new Exception("Không đủ số lượng sản phẩm trong kho.");
        }

        $stmt->bind_param("iiiid", $order_id, $product_data['variant_id'], $product_data['sku_id'], $item['quantity'], $item['price']);
        $stmt->execute();

        $update_stock_stmt->bind_param("ii", $item['quantity'], $product_data['sku_id']);
        $update_stock_stmt->execute();
    }

    $conn->commit();
    
    if ($payment_method === 'VNPAY') {
        echo json_encode([
            'success' => true,
            'order_id' => $order_id,
            'payment_id' => $payment_id,
            'payment_method' => 'VNPAY',
            'amount' => $total_amount,
            'redirect_url' => './vnpay_php/vnpay_create_payment.php'
        ]);
    } else {
        $update_order = "UPDATE orders SET status = 'Processing' WHERE order_id = ?";
        $stmt = $conn->prepare($update_order);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();

        echo json_encode([
            'success' => true,
            'order_id' => $order_id,
            'payment_method' => 'COD',
            'message' => 'Đặt hàng thành công! Bạn sẽ thanh toán khi nhận hàng.'
        ]);
    }
    exit;
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();