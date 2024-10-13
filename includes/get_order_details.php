<?php
include 'db_connect.php'; // Kết nối cơ sở dữ liệu
session_start();

if (isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];

    // Truy vấn chi tiết đơn hàng
    $stmt = $conn->prepare("SELECT 
    orders.order_id,
    orders.order_date,
    orders.total,
    orders.status,
    order_items.quantity,
    order_items.size,
    order_items.color,
    products.product_name,
    products.price
    
FROM orders
JOIN order_items ON orders.order_id = order_items.order_id
JOIN products ON order_items.order_item_id = products.product_id
WHERE orders.order_id = ?;");
    
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result_details = $stmt->get_result();

    // Hiển thị kết quả trong một bảng Bootstrap
    echo '<div class="container mt-5">';
    echo '<h2>Chi tiết đơn hàng</h2>';
    echo '<table class="table table-bordered">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Tên sản phẩm</th>';
    echo '<th>Số lượng</th>';
    echo '<th>Kích thước</th>';
    echo '<th>Màu sắc</th>';
    echo '<th>Giá</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    if ($result_details->num_rows > 0) {
        while ($detail_row = $result_details->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($detail_row['product_name']) . '</td>';
            echo '<td>' . htmlspecialchars($detail_row['quantity']) . '</td>';
            echo '<td>' . htmlspecialchars($detail_row['size']) . '</td>';
            echo '<td>' . htmlspecialchars($detail_row['color']) . '</td>';
            echo '<td>₫' . number_format($detail_row['price'], 0, ',', '.') . '</td>';
            echo '</tr>';
            $total = $detail_row['total'];
        }
    } else {
        echo '<tr><td colspan="5" class="text-center">Không có sản phẩm nào.</td></tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '<p>Tổng: ₫' . number_format($total, 0, ',', '.') .  '</p>'; // Tổng tiền
    echo '</div>'; // Đóng container
}
?>
