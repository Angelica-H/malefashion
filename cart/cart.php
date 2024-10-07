<?php
session_start();

// Kiểm tra nếu có dữ liệu POST từ Ajax
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu sản phẩm từ yêu cầu Ajax
    $productId = $_POST['product_id'];
    $productName = $_POST['product_name'];
    $productPrice = $_POST['product_price'];
    $productQuantity = $_POST['quantity'];

    // Tạo đối tượng sản phẩm
    $product = [
        'id' => $productId,
        'name' => $productName,
        'price' => $productPrice,
        'quantity' =>  1
    ];

    // Kiểm tra nếu giỏ hàng đã tồn tại
    if (isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];

        // Nếu sản phẩm đã tồn tại trong giỏ hàng thì tăng số lượng
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += 1;
        } else {
            // Thêm sản phẩm mới vào giỏ hàng
            $cart[$productId] = $product;
        }
    } else {
        // Tạo giỏ hàng mới nếu chưa có
        $cart = [$productId => $product];
    }

    // Lưu lại giỏ hàng trong session
    $_SESSION['cart'] = $cart;

    // Trả về phản hồi JSON
    echo json_encode(['status' => 'success', 'message' => 'Sản phẩm đã được thêm vào giỏ hàng']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Yêu cầu không hợp lệ']);
}
