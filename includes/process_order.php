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
    $order_query = "INSERT INTO orders (customer_id, order_date, total, status, shipping_address, payment_method) VALUES (?, NOW(), ?, 'Pending', ?, ?)";
    $stmt = $conn->prepare($order_query);
    $stmt->bind_param("idss", $customer_id, $total_amount, $shipping_address, $payment_method);
    $stmt->execute();
    $order_id = $conn->insert_id;

    $order_item_query = "INSERT INTO order_items (order_id, variant_id, sku_id, quantity, price) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($order_item_query);
    
    $get_product_query = "SELECT pv.variant_id, s.sku_id, s.stock FROM product_variants pv 
                          JOIN sku s ON pv.variant_id = s.variant_id 
                          WHERE pv.product_id = ? AND pv.color_id = ? AND pv.size_id = ?";
    $get_product_stmt = $conn->prepare($get_product_query);
    
    $update_stock_query = "UPDATE sku SET stock = stock - ? WHERE sku_id = ?";
    $update_stock_stmt = $conn->prepare($update_stock_query);

    foreach ($cart_data as $item) {
        // Kiểm tra số lượng sản phẩm và lấy variant_id và sku_id
        $get_product_stmt->bind_param("iii", $item['product_id'], $item['color_id'], $item['size_id']);
        $get_product_stmt->execute();
        $product_result = $get_product_stmt->get_result();
        $product_data = $product_result->fetch_assoc();

        if (!$product_data) {
            throw new Exception("Không tìm thấy sản phẩm hoặc SKU phù hợp.");
        }

        $available_quantity = $product_data['stock'];
        $variant_id = $product_data['variant_id'];
        $sku_id = $product_data['sku_id'];

        if ($available_quantity < $item['quantity']) {
            throw new Exception("Không đủ số lượng sản phẩm trong kho.");
        }

        // Thêm vào order_items
        $stmt->bind_param("iiiid", $order_id, $variant_id, $sku_id, $item['quantity'], $item['price']);
        $stmt->execute();

        // Cập nhật số lượng trong bảng sku
        $update_stock_stmt->bind_param("ii", $item['quantity'], $sku_id);
        $update_stock_stmt->execute();
    }

    $conn->commit();
    
    // Xóa giỏ hàng sau khi đặt hàng thành công
    // Xóa giỏ hàng từ localStorage
    echo "<script>
        localStorage.removeItem('cart');
        localStorage.removeItem('cartCount');
        localStorage.removeItem('cartTotal');
    </script>";
    
    echo json_encode(['success' => true, 'order_id' => $order_id]);
    exit;
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();