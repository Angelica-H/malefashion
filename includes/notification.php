<?php
if (isset($_SESSION['message'])) {
    echo "<div class='alert alert-success' style='padding: 30px; margin: 20px 50px 70px;'>"
        . $_SESSION['message'] . "</div>";
    unset($_SESSION['message']);
}

if (isset($_SESSION['error_message'])) {
    echo "<div class='alert alert-danger' style='padding: 30px; margin: 20px 50px 70px;'>"
        . $_SESSION['error_message'] . "</div>";
    unset($_SESSION['error_message']);
}
?>