<?php
session_start();

function checkAdminSession() {
    // Kiểm tra xem phiên đã được khởi tạo và có chứa user_id không
    if (!isset($_SESSION['user_id'])) {
        redirectToLogin();
        return;
    }

    // Kiểm tra vai trò người dùng
    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['Admin', 'Super Admin'])) {
        redirectToLogin();
        return;
    }
}

function redirectToLogin() {
    header("Location: login.php");
    exit();
}
?>
