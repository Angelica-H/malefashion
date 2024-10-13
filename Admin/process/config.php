<?php
// config.php
$host = 'localhost'; // Thay đổi nếu cần
$db = 'male-fashion'; // Tên cơ sở dữ liệu
$user = 'root'; // Tên người dùng
$pass = ''; // Mật khẩu

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $db :" . $e->getMessage());
}
?>