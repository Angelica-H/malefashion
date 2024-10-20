<?php
include 'db_connect.php'; // Kết nối cơ sở dữ liệu
session_start();

if (isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];

    // Truy vấn chi tiết đơn hàng
    $stmt = $conn->prepare("SELECT 
        oi.quantity,
        oi.size,
        oi.color,
        oi.price as item_price,
        p.product_name,
        o.total,
        o.status
    FROM order_items oi
    JOIN products p ON oi.variant_id = p.product_id
    JOIN orders o ON oi.order_id = o.order_id
    WHERE oi.order_id = ?");
    
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result_details = $stmt->get_result();

    $total = 0;
    $order_status = '';

    if ($result_details->num_rows > 0) {
        while ($detail_row = $result_details->fetch_assoc()) {
            $subtotal = $detail_row['quantity'] * $detail_row['item_price'];
            echo '<tr>';
            echo '<td>' . htmlspecialchars($detail_row['product_name']) . '</td>';
            echo '<td>' . htmlspecialchars($detail_row['size']) . '</td>';
            echo '<td>' . htmlspecialchars($detail_row['color']) . '</td>';
            echo '<td>' . htmlspecialchars($detail_row['quantity']) . '</td>';
            echo '<td>' . number_format($detail_row['item_price'], 0, ',', '.') . ' đ</td>';
            echo '<td>' . number_format($subtotal, 0, ',', '.') . ' đ</td>';
            echo '</tr>';
            $total = $detail_row['total'];
            $order_status = $detail_row['status'];
        }
        
        // Hiển thị tổng cộng và trạng thái đơn hàng
        echo '<tr class="table-active">';
        echo '<td colspan="5" class="text-right"><strong>Tổng cộng:</strong></td>';
        echo '<td><strong>' . number_format($total, 0, ',', '.') . ' đ</strong></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td colspan="5" class="text-right"><strong>Trạng thái đơn hàng:</strong></td>';
        echo '<td><strong>' . htmlspecialchars($order_status) . '</strong></td>';
        echo '</tr>';
    } else {
        echo '<tr><td colspan="6" class="text-center">Không có sản phẩm nào trong đơn hàng này.</td></tr>';
    }
}
?>