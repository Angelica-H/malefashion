<?php
include 'includes/db_connect.php'; // Kết nối cơ sở dữ liệu
session_start();
$sql = "SELECT orders.order_id, 
               CONCAT(customers.first_name, ' ', customers.last_name) AS receiver_name,
               customers.shipping_address,
               customers.phone_number,
               orders.total,
               orders.status
        FROM orders
        JOIN customers ON orders.customer_id = customers.customer_id";
$result = $conn->query($sql);
//echo '<pre>';
//print_r($_SESSION['cart']);
//echo '</pre>';
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Male_Fashion Template">
    <meta name="keywords" content="Male_Fashion, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Male-Fashion | Template</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Css Styles -->
    <?php include "includes/css.php" ?>

</head>

<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Offcanvas Menu Begin -->
    <?php include "includes/menu_begin.php" ?>
    <!-- Offcanvas Menu End -->

    <!-- Header Section Begin -->
    <?php include "includes/header_section.php" ?>
    <!-- Header Section End -->

    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Shopping Cart</h4>
                        <div class="breadcrumb__links">
                            <a href="./index.html">Home</a>
                            <a href="./shop.html">Shop</a>
                            <span>Shopping Cart</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->
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
                    // Lặp qua tất cả các đơn hàng và hiển thị
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['order_id'] . "</td>";
                        echo "<td>" . $row['receiver_name'] . "<br>" . $row['shipping_address'] . "<br>SĐT: " . $row['phone_number'] . "</td>";
                        echo "<td>₫" . number_format($row['total'], 0, ',', '.') . "</td>";
                        echo "<td>" . $row['status'] . "</td>";
                        echo "<td><button class='btn btn-info' data-toggle='modal' data-target='#orderDetailsModal" . $row['order_id'] . "'>Xem Chi Tiết</button></td>";
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
                                        <ul>";
                        // Truy vấn để lấy chi tiết đơn hàng
                        $order_id = $row['order_id'];
                        $sql_details = "SELECT product_variants.product_name, order_items.quantity, order_items.price 
                                        FROM order_items 
                                        JOIN product_variants ON order_items.variant_id = product_variants.variant_id 
                                        WHERE order_items.order_id = '$order_id'";
                        $result_details = $conn->query($sql_details);
                        if ($result_details->num_rows > 0) {
                            while ($detail_row = $result_details->fetch_assoc()) {
                                echo "<li>" . $detail_row['product_name'] . " - " . $detail_row['quantity'] . " x ₫" . number_format($detail_row['price'], 0, ',', '.') . "</li>";
                            }
                        }
                        echo "        </ul>
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

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Footer Section Begin -->
<?php include "includes/footer_section.php" ?>
<!-- Footer Section End -->

<!-- Search Begin -->
<div class="search-model">
    <div class="h-100 d-flex align-items-center justify-content-center">
        <div class="search-close-switch">+</div>
        <form class="search-model-form">
            <input type="text" id="search-input" placeholder="Search here.....">
        </form>
    </div>
</div>

    <!-- Search End -->

    <!-- Js Plugins -->
    <?php include "includes/js.php" ?>

</body>

</html>
<?php
// Đóng kết nối
$conn->close();
?>