<?php
require 'config.php';
require 'check_admin_session.php';

checkAdminSession();

// Get filter parameters
$search = isset($_GET['search']) ? $_GET['search'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'order_date';
$order = isset($_GET['order']) ? $_GET['order'] : 'DESC';

// Build the SQL query (similar to your existing query in order.php)
$sql = "SELECT o.order_id, o.order_date, o.total, o.status, o.payment_method,
               c.first_name, c.last_name,
               (SELECT GROUP_CONCAT(p.product_name SEPARATOR ', ')
                FROM order_items oi
                JOIN product_variants pv ON oi.variant_id = pv.variant_id
                JOIN products p ON pv.product_id = p.product_id
                WHERE oi.order_id = o.order_id) AS product_list
        FROM orders o
        JOIN customers c ON o.customer_id = c.customer_id
        WHERE 1=1";

if (!empty($search)) {
    $sql .= " AND (o.order_id LIKE :search OR c.first_name LIKE :search OR c.last_name LIKE :search)";
}

if (!empty($status)) {
    $sql .= " AND o.status = :status";
}

$sql .= " ORDER BY $sort $order";

try {
    $stmt = $pdo->prepare($sql);
    if (!empty($search)) {
        $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
    }
    if (!empty($status)) {
        $stmt->bindValue(':status', $status, PDO::PARAM_STR);
    }
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Generate and return the HTML for the filtered orders
    foreach ($orders as $order) {
        echo "<tr>";
        echo "<td><strong>#" . $order['order_id'] . "</strong></td>";
        echo "<td>" . htmlspecialchars($order['first_name'] . ' ' . $order['last_name']) . "</td>";
        echo "<td><small>" . htmlspecialchars(substr($order['product_list'], 0, 50) . '...') . "</small></td>";
        echo "<td><strong>" . number_format($order['total'], 0, ',', '.') . " ₫</strong></td>";
        echo "<td>";
        echo "<select class='form-control status-select' data-order-id='" . $order['order_id'] . "' style='width: auto; min-width: 120px; font-size: 0.9em; padding: 0.2em;'>";
        $statuses = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];
        $classes = ['warning', 'primary', 'info', 'success', 'danger'];
        foreach ($statuses as $index => $s) {
            $selected = ($order['status'] == $s) ? 'selected' : '';
            echo "<option value='$s' data-class='{$classes[$index]}' $selected>$s</option>";
        }
        echo "</select>";
        echo "</td>";
        echo "<td>" . htmlspecialchars($order['payment_method']) . "</td>";
        echo "<td>";
        echo "<a href='order-show.php?id=" . $order['order_id'] . "' class='btn btn-primary btn-sm'>";
        echo "<i class='fa fa-info-circle'></i> Details";
        echo "</a>";
        echo "</td>";
        echo "</tr>";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

