<?php
include 'includes/db_connect.php'; // Kết nối cơ sở dữ liệu
session_start();

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$sql = "SELECT orders.order_id, 
               CONCAT(customers.first_name, ' ', customers.last_name) AS receiver_name,
               customers.shipping_address,
               customers.phone_number,
               orders.total,
               orders.order_date,
               orders.status
        FROM orders
        JOIN customers ON orders.customer_id = customers.customer_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Đơn Hàng</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Css Styles -->
    <?php include "includes/css.php"; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <!-- Offcanvas Menu Begin -->
    <?php include "includes/menu_begin.php"; ?>
    <!-- Offcanvas Menu End -->

    <!-- Header Section Begin -->
    <?php include "includes/header_section.php"; ?>
    <!-- Header Section End -->

    <div class="container mt-5">
    <h2 class="text-center">Danh Sách Đơn Hàng</h2>

    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>Mã Đơn Hàng</th>
                <th>Ngày đặt</th>
                <th>Thông Tin Người Nhận</th>
                <th>Tổng Tiền</th>
                <th>Trạng Thái Đơn Hàng</th>
                <th>Chi Tiết</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['order_id'] . "</td>";
                    echo "<td>" . $row['order_date'] . "</td>";
                    echo "<td>" . $row['shipping_address'] . "</td>";
                    echo "<td>" . number_format($row['total'], 0, ',', '.') . " đ</td>";
                    echo "<td>" . $row['status'] . "</td>";
                    echo "<td>
                        <button class='btn btn-info view-details' data-toggle='modal' data-target='#orderDetailsModal' data-id='" . $row['order_id'] . "'>Xem Chi Tiết</button>
                    </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6' class='text-center'>Không có đơn hàng nào.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Modal cho chi tiết đơn hàng -->
<div class='modal fade' id='orderDetailsModal' tabindex='-1' role='dialog' aria-labelledby='orderDetailsModalLabel' aria-hidden='true'>
    <div class='modal-dialog modal-lg' role='document'>
        <div class='modal-content'>
            <div class='modal-header'>
                <h5 class='modal-title' id='orderDetailsModalLabel'>Chi Tiết Đơn Hàng</h5>
                <button type='button' class='close' data-dismiss='modal' aria-label='Đóng'>
                    <span aria-hidden='true'>&times;</span>
                </button>
            </div>
            <div class='modal-body'>
                <table class='table'>
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Kích thước</th>
                            <th>Màu sắc</th>
                            <th>Số lượng</th>
                            <th>Giá</th>
                            <th>Tổng</th>
                        </tr>
                    </thead>
                    <tbody id='order-details-list'>
                        <!-- Chi tiết sẽ được chèn vào đây -->
                    </tbody>
                </table>
            </div>
            <div class='modal-footer'>
                <button type='button' class='btn btn-secondary' data-dismiss='modal'>Đóng</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.view-details').click(function() {
        var orderId = $(this).data('id');
        
        $.ajax({
            url: 'includes/get_order_details.php',
            type: 'POST',
            data: { order_id: orderId },
            success: function(response) {
                $('#order-details-list').html(response);
                $('#orderDetailsModalLabel').text('Chi Tiết Đơn Hàng ' + orderId);
            },
            error: function(xhr, status, error) {
                console.error("Lỗi: " + error);
            }
        });
    });
});
</script>

    <!-- Footer Section Begin -->
    <?php include "includes/footer_section.php"; ?>
    <!-- Footer Section End -->
</body>
</html>

<?php
$conn->close();
?>
