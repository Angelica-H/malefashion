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
                        echo "<td>" . $row['receiver_name'] . "<br>" . $row['shipping_address'] . "<br>SĐT: " . $row['phone_number'] . "</td>";
                        echo "<td>₫" . number_format($row['total'], 0, ',', '.') . "</td>";
                        echo "<td>" . $row['status'] . "</td>";
                        echo "<td>
                            <button class='btn btn-info' data-toggle='modal' data-target='#orderDetailsModal" . $row['order_id'] . "' data-id='" . $row['order_id'] . "'>Xem Chi Tiết</button>
                        </td>";
                        echo "</tr>";

                        // Modal cho chi tiết đơn hàng
                        echo "<div class='modal fade' id='orderDetailsModal" . $row['order_id'] . "' tabindex='-1' role='dialog' aria-labelledby='orderDetailsModalLabel" . $row['order_id'] . "' aria-hidden='true'>
                            <div class='modal-dialog' role='document'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h5 class='modal-title' id='orderDetailsModalLabel" . $row['order_id'] . "'>Chi Tiết Đơn Hàng " . $row['order_id'] . "</h5>
                                        <button type='button' class='close' data-dismiss='modal' aria-label='Đóng'>
                                            <span aria-hidden='true'>&times;</span>
                                        </button>
                                    </div>
                                    <div class='modal-body'>
                                        <ul id='order-details-list-" . $row['order_id'] . "'>
                                            <!-- Chi tiết sẽ được chèn vào đây -->
                                        </ul>
                                    </div>
                                    <div class='modal-footer'>
                                        <button type='button' class='btn btn-secondary' data-dismiss='modal'>Đóng</button>
                                    </div>
                                </div>
                            </div>
                        </div>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>Không có đơn hàng nào.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            $('.btn-info').click(function() {
                var orderId = $(this).data('id'); // Lấy ID đơn hàng từ thuộc tính data-id

                // Gọi AJAX để lấy chi tiết đơn hàng
                $.ajax({
                    url: 'includes/get_order_details.php', // Tệp xử lý để lấy chi tiết đơn hàng
                    type: 'POST',
                    data: { order_id: orderId },
                    success: function(response) {
                        // Chèn dữ liệu vào modal
                        $('#order-details-list-' + orderId).html(response);
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