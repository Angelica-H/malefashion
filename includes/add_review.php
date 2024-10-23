<?php
session_start();
require_once '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['customer_id'])) {
    $product_id = $_POST['product_id'];
    $customer_id = $_SESSION['customer_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    // Thêm đánh giá mới
    $sql = "INSERT INTO product_reviews (product_id, customer_id, rating, comment) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiis", $product_id, $customer_id, $rating, $comment);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Đánh giá của bạn đã được gửi thành công!";
        
        // Cập nhật rating trung bình cho sản phẩm
        $updateAvgRatingQuery = "UPDATE products SET avg_rating = (SELECT AVG(rating) FROM product_reviews WHERE product_id = ?) WHERE product_id = ?";
        $updateStmt = $conn->prepare($updateAvgRatingQuery);
        $updateStmt->bind_param("ii", $product_id, $product_id);
        $updateStmt->execute();
        $updateStmt->close();
    } else {
        $_SESSION['error_message'] = "Có lỗi xảy ra khi gửi đánh giá.";
    }

    $stmt->close();
    
    header("Location: ../shop-details.php?id=" . $product_id);
    exit();
} else {
    header("Location: ../login.php");
    exit();
}

$conn->close();
?>