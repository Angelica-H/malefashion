<?php
include 'db_connect.php'; // Kết nối cơ sở dữ liệu
session_start();

if (isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];

    // Truy vấn chi tiết đơn hàng
    $stmt = $conn->prepare("SELECT product_variants.product_name, order_items.quantity, order_items.price 
                            FROM order_items 
                            JOIN product_variants ON order_items.variant_id = product_variants.variant_id 
                            WHERE order_items.order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result_details = $stmt->get_result();

    if ($result_details->num_rows > 0) {
        while ($detail_row = $result_details->fetch_assoc()) {
            echo "<li>" . $detail_row['product_name'] . " - " . $detail_row['quantity'] . " x ₫" . number_format($detail_row['price'], 0, ',', '.') . "</li>";
        }
    } else {
        echo "<li>Không có sản phẩm nào.</li>";
    }
}
?>
