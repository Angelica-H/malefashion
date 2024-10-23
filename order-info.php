<?php
include 'includes/db_connect.php';
session_start();

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$customer_id = $_SESSION['customer_id'];

$sql = "SELECT o.order_id, 
               o.order_date,
               CONCAT(c.first_name, ' ', c.last_name) AS receiver_name,
               c.phone_number,
               o.shipping_address,
               o.total,
               o.status,
               o.payment_method
        FROM orders o
        JOIN customers c ON o.customer_id = c.customer_id
        WHERE o.customer_id = ?
        ORDER BY o.order_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
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
                    <th>Ngày Đặt</th>
                    <th>Khách Hàng</th>
                    <th>Tổng Tiền</th>
                    <th>Trạng Thái</th>
                    <th>Chi Tiết</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td class='align-middle'>" . $row['order_id'] . "</td>";
                        echo "<td class='align-middle'>" . date('d/m/Y H:i', strtotime($row['order_date'])) . "</td>";
                        echo "<td class='align-middle'>" . ($row['receiver_name'] ?? 'N/A') . "</td>";
                        echo "<td class='align-middle'>" . number_format($row['total'], 0, ',', '.') . " đ</td>";
                        echo "<td class='align-middle'><span class='badge badge-" . ($row['status'] == 'Đã giao hàng' ? 'success' : 'warning') . "'>" . $row['status'] . "</span></td>";
                        echo "<td class='align-middle'>
                            <button class='btn btn-outline-info btn-sm view-details' data-id='" . $row['order_id'] . "'>
                                <i class='fa fa-eye mr-1'></i> Xem Chi Tiết
                            </button>
                        </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center text-muted'>Không có đơn hàng nào.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

 <!-- Modal cho chi tiết đơn hàng -->
 <div class="modal fade" id="orderDetailsModal" tabindex="-1" role="dialog" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderDetailsModalLabel">Chi Tiết Đơn Hàng</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="orderDetailsContent">
                    <!-- Nội dung chi tiết đơn hàng sẽ được chèn ở đây -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    $(document).ready(function() {
        $('.view-details').click(function() {
            var orderId = $(this).data('id');
            
            $.ajax({
                url: 'includes/get_order_details.php',
                type: 'POST',
                data: { order_id: orderId },
                success: function(response) {
                    $('#orderDetailsContent').html(response);
                    $('#orderDetailsModal').modal('show');
                },
                error: function() {
                    alert('Đã xảy ra lỗi khi tải chi tiết đơn hàng.');
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
