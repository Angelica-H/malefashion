<?php
require_once 'config.php';
require_once 'check_admin_session.php';
checkAdminSession();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'] ?? null;
    $user_type = $_POST['user_type'] ?? null;

    if (!$user_id || !$user_type) {
        $_SESSION['error_message'] = "Thông tin người dùng không hợp lệ.";
        header("Location: ../index.php");
        exit();
    }

    try {
        // Kiểm tra vai trò của người dùng hiện tại
        $admin_id = $_SESSION['user_id'];
        $stmt = $pdo->prepare("SELECT role FROM admin WHERE admin_id = ?");
        $stmt->execute([$admin_id]);
        $current_admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user_type === 'Admin') {
            // Kiểm tra xem admin cần xóa có phải là Super Admin không
            $stmt = $pdo->prepare("SELECT role FROM admin WHERE admin_id = ?");
            $stmt->execute([$user_id]);
            $admin_to_delete = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin_to_delete['role'] === 'Super Admin' && $current_admin['role'] !== 'Super Admin') {
                $_SESSION['error_message'] = "Chỉ Super Admin mới có thể xóa Super Admin khác.";
                header("Location: ../index.php");
                exit();
            }

            if ($current_admin['role'] !== 'Super Admin') {
                $_SESSION['error_message'] = "Chỉ Super Admin mới có thể xóa các admin khác.";
                header("Location: ../index.php");
                exit();
            }

            $stmt = $pdo->prepare("DELETE FROM admin WHERE admin_id = ?");
        } else {
            // Nếu là Customer, cả Admin và Super Admin đều có thể xóa
            if ($current_admin['role'] !== 'Super Admin' && $current_admin['role'] !== 'Admin') {
                $_SESSION['error_message'] = "Bạn không có quyền xóa người dùng.";
                header("Location: ../index.php");
                exit();
            }
            $stmt = $pdo->prepare("DELETE FROM customers WHERE customer_id = ?");
        }

        $stmt->execute([$user_id]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['success_message'] = "Đã xóa người dùng thành công.";
        } else {
            $_SESSION['error_message'] = "Không thể xóa người dùng.";
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Lỗi: " . $e->getMessage();
    }

    header("Location: ../index.php");
    exit();
} else {
    header("Location: ../index.php");
    exit();
}
