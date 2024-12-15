<?php
include 'db_connect.php';

if (isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    
    // Lấy thông tin đơn hàng và phương thức thanh toán từ bảng payments
    $order_sql = "SELECT o.*, p.payment_method, CONCAT(c.first_name, ' ', c.last_name) AS customer_name, c.phone_number
                  FROM orders o
                  JOIN customers c ON o.customer_id = c.customer_id
                  JOIN payments p ON o.order_id = p.order_id
                  WHERE o.order_id = ?";
    $stmt = $conn->prepare($order_sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $order_result = $stmt->get_result();
    $order = $order_result->fetch_assoc();

    // Lấy chi tiết sản phẩm trong đơn hàng
    $items_sql = "SELECT oi.*, p.product_name, p.product_image, v.size_id, v.color_id, s.size_name, c.color_name
                  FROM order_items oi
                  JOIN product_variants v ON oi.variant_id = v.variant_id
                  JOIN products p ON v.product_id = p.product_id
                  JOIN sizes s ON v.size_id = s.size_id
                  JOIN colors c ON v.color_id = c.color_id
                  WHERE oi.order_id = ?";
    $stmt = $conn->prepare($items_sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $items_result = $stmt->get_result();

    // Hiển thị thông tin đơn hàng
    echo "<div class='container'>";
    echo "<div class='card mb-4'>";
    echo "<div class='card-header bg-primary text-white'>";
    echo "<h4 class='mb-0'>Đơn hàng #" . $order['order_id'] . "</h4>";
    echo "</div>";
    echo "<div class='card-body'>";
    echo "<div class='row'>";
    echo "<div class='col-md-6'>";
    echo "<p><strong>Khách hàng:</strong> " . $order['customer_name'] . "</p>";
    echo "<p><strong>Số điện thoại:</strong> " . $order['phone_number'] . "</p>";
    echo "<p><strong>Địa chỉ giao hàng:</strong> " . $order['shipping_address'] . "</p>";
    echo "</div>";
    echo "<div class='col-md-6'>";
    echo "<p><strong>Ngày đặt hàng:</strong> " . date('d/m/Y H:i', strtotime($order['order_date'])) . "</p>";
    echo "<p><strong>Trạng thái:</strong> <span class='badge bg-" . ($order['status'] == 'Đã giao hàng' ? 'success' : 'warning') . "'>" . $order['status'] . "</span></p>";
    echo "<p><strong>Phương thức thanh toán:</strong> " . $order['payment_method'] . "</p>"; // Lấy phương thức thanh toán từ bảng payments
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";

    // Hiển thị chi tiết sản phẩm
    echo "<div class='card'>";
    echo "<div class='card-header bg-secondary text-white'>";
    echo "<h5 class='mb-0'>Chi tiết sản phẩm</h5>";
    echo "</div>";
    echo "<div class='card-body'>";
    echo "<div class='table-responsive'>";
    echo "<table class='table table-striped table-hover'>";
    echo "<thead class='table-light'><tr><th>Ảnh</th><th>Sản phẩm</th><th>Kích thước</th><th>Màu sắc</th><th>Số lượng</th><th>Giá</th><th>Tổng</th></tr></thead>";
    echo "<tbody>";
    
    $total = 0;
    while ($item = $items_result->fetch_assoc()) {
        $subtotal = $item['quantity'] * $item['price'];
        $total += $subtotal;
        echo "<tr>";
        echo "<td><img src='" . $item['product_image'] . "' alt='" . $item['product_name'] . "' style='width: 50px; height: 50px; object-fit: cover;'></td>";
        echo "<td>" . $item['product_name'] . "</td>";
        echo "<td>" . $item['size_name'] . "</td>";
        echo "<td>" . $item['color_name'] . "</td>";
        echo "<td>" . $item['quantity'] . "</td>";
        echo "<td>" . number_format($item['price'], 0, ',', '.') . " đ</td>";
        echo "<td>" . number_format($subtotal, 0, ',', '.') . " đ</td>";
        echo "</tr>";
    }
    
    echo "</tbody>";
    echo "<tfoot class='table-light'><tr><td colspan='6' class='text-end'><strong>Tổng cộng:</strong></td><td><strong>" . number_format($total, 0, ',', '.') . " đ</strong></td></tr></tfoot>";
    echo "</table>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";

    $stmt->close();
} else {
    echo "Không tìm thấy thông tin đơn hàng.";
}

$conn->close();
?>