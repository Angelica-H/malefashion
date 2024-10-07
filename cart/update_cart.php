<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Kiểm tra nếu sản phẩm đã có trong giỏ hàng
    if (isset($_SESSION['cart'][$productId])) {
        // Cập nhật số lượng sản phẩm
        $_SESSION['cart'][$productId]['quantity'] = $quantity;

        // Chuyển hướng về trang giỏ hàng
        header("Location: cart_view.php");
        exit;
    }
}
