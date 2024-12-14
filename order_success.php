<?php
session_start();
include "includes/db_connect.php";

if (!isset($_GET['order_id'])) {
    header('Location: index.php');
    exit();
}

$order_id = $_GET['order_id'];

// Lấy thông tin đơn hàng
$order_query = "SELECT o.*, p.payment_method, p.status as payment_status 
                FROM orders o 
                LEFT JOIN payments p ON o.order_id = p.order_id 
                WHERE o.order_id = ?";
$stmt = $conn->prepare($order_query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

// Lấy chi tiết đơn hàng
$items_query = "SELECT oi.*, p.product_name, c.color_name, s.size_name, p.product_image
                FROM order_items oi
                JOIN product_variants pv ON oi.variant_id = pv.variant_id
                JOIN products p ON pv.product_id = p.product_id
                JOIN colors c ON pv.color_id = c.color_id
                JOIN sizes s ON pv.size_id = s.size_id
                WHERE oi.order_id = ?";
$stmt = $conn->prepare($items_query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt hàng thành công - Male Fashion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <?php include "includes/css.php"; ?>
</head>

<body>
    <?php include "includes/header_section.php"; ?>

    <div class="container py-5">
        <!-- Thông báo thành công -->
        <div class="row justify-content-center mb-5">
            <div class="col-md-8 text-center">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5">
                        <div class="display-4 text-success mb-4">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h2 class="mb-3">Đặt hàng thành công!</h2>
                        <p class="text-muted">Cảm ơn bạn đã đặt hàng. Mã đơn hàng của bạn là:</p>
                        <h4 class="text-primary">#<?php echo $order_id; ?></h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Chi tiết đơn hàng -->
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Chi tiết đơn hàng</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <strong>Ngày đặt:</strong><br>
                                    <?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?>
                                </p>
                                <p class="mb-2">
                                    <strong>Trạng thái đơn hàng:</strong><br>
                                    <span class="badge bg-info"><?php echo $order['status']; ?></span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <strong>Địa chỉ giao hàng:</strong><br>
                                    <?php echo $order['shipping_address']; ?>
                                </p>
                            </div>
                        </div>

                        <!-- Danh sách sản phẩm -->
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Giá</th>
                                        <th class="text-center">Số lượng</th>
                                        <th class="text-end">Tổng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="<?php echo $item['product_image']; ?>" 
                                                     class="img-fluid rounded" 
                                                     style="width: 60px; height: 60px; object-fit: cover;">
                                                <div class="ms-3">
                                                    <h6 class="mb-1"><?php echo $item['product_name']; ?></h6>
                                                    <small class="text-muted">
                                                        Màu: <?php echo $item['color_name']; ?>, 
                                                        Size: <?php echo $item['size_name']; ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo number_format($item['price'], 0, ',', '.'); ?>đ</td>
                                        <td class="text-center"><?php echo $item['quantity']; ?></td>
                                        <td class="text-end">
                                            <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?>đ
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông tin thanh toán -->
            <div class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Thông tin thanh toán</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <p class="mb-2"><strong>Phương thức thanh toán:</strong></p>
                            <p class="mb-0"><?php echo $order['payment_method']; ?></p>
                        </div>
                        <div class="mb-3">
                            <p class="mb-2"><strong>Trạng thái thanh toán:</strong></p>
                            <span class="badge bg-<?php echo $order['payment_status'] == 'Paid' ? 'success' : 'warning'; ?>">
                                <?php echo $order['payment_status']; ?>
                            </span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Tổng tiền:</h5>
                            <h5 class="mb-0 text-primary">
                                <?php echo number_format($order['total'], 0, ',', '.'); ?>đ
                            </h5>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="d-grid gap-2">
                    <a href="shop.php" class="btn btn-primary">
                        <i class="fas fa-shopping-bag me-2"></i>Tiếp tục mua sắm
                    </a>
                    <a href="order-info.php" class="btn btn-outline-primary">
                        <i class="fas fa-history me-2"></i>Xem lịch sử đơn hàng
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php include "includes/footer_section.php"; ?>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include "includes/js.php"; ?>
</body>
</html>
