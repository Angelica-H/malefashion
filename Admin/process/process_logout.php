<?php
session_start();

// Xóa tất cả các biến session
$_SESSION = array();

// Hủy phiên đăng nhập
session_destroy();

// Xóa cookie phiên làm việc nếu có
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Chuyển hướng về trang đăng nhập
header("Location: ../login.php");
exit();
?>
