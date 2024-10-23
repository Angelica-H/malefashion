<?php
require 'config.php';
require 'check_admin_session.php';

checkAdminSession();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    try {
        $stmt = $pdo->prepare("UPDATE orders SET status = :status WHERE order_id = :order_id");
        $stmt->execute([
            ':status' => $status,
            ':order_id' => $order_id
        ]);

        if ($stmt->rowCount() > 0) {
            echo 'success';
        } else {
            echo 'error';
        }
    } catch (PDOException $e) {
        echo 'error';
    }
} else {
    echo 'error';
}