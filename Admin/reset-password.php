<?php
require 'process/config.php';
require 'process/db_connect.php';

if (!isset($_GET['token'])) {
    header("Location: " . BASE_URL . "login.php");
    exit();
}

$token = $_GET['token'];

// Kiểm tra token có hợp lệ không
$stmt = $conn->prepare("SELECT email FROM password_resets WHERE token = ? AND expires > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Invalid or expired token.";
    exit();
}

$email = $result->fetch_assoc()['email'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <!-- Thêm các file CSS cần thiết -->
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        <form action="process/process_reset_password.php" method="POST">
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <div class="form-group">
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary">Reset Password</button>
        </form>
    </div>
    <!-- Thêm các file JavaScript cần thiết -->
</body>
</html>

