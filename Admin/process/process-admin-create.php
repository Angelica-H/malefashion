<?php
require 'config.php';
require '../includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $status = $_POST['status'];

    try {
        $stmt = $pdo->prepare("INSERT INTO admin (username, email, password, role, status) VALUES (?, ?, ?, ?, ?)");
        $result = $stmt->execute([$username, $email, $password, $role, $status]);

        if ($result) {
            $_SESSION['success_message'] = "Admin created successfully!";
            header("Location: ../index.php");
            exit();
        } else {
            throw new Exception("Error creating admin");
        }
    } catch (PDOException $e) {
        if ($e->getCode() == '23000') {
            $_SESSION['error_message'] = "Username or email already exists.";
        } else {
            $_SESSION['error_message'] = "Database error: " . $e->getMessage();
        }
        header("Location: ../admin-create.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
        header("Location: ../admin-create.php");
        exit();
    }
} else {
    header("Location: ../admin-create.php");
    exit();
}