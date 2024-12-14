<?php
require_once("./config.php"); 
require_once("../includes/db_connect.php");

// Khởi tạo biến $isSuccess với giá trị mặc định là false
$isSuccess = false;

// Khởi tạo dữ liệu input
$inputData = array();
foreach ($_GET as $key => $value) {
    if (substr($key, 0, 4) == "vnp_") {
        $inputData[$key] = $value;
    }
}

// Thêm code xử lý thanh toán ở đây
// Xác thực chữ ký
$vnp_SecureHash = $inputData['vnp_SecureHash'];
unset($inputData['vnp_SecureHash']);
ksort($inputData);
$hashData = "";
$i = 0;
foreach ($inputData as $key => $value) {
    if ($i == 1) {
        $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
    } else {
        $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
        $i = 1;
    }
}

$secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

// Lấy thông tin giao dịch
$vnp_TxnRef = $inputData['vnp_TxnRef']; 
$vnp_Amount = $inputData['vnp_Amount']/100;
$vnp_ResponseCode = $inputData['vnp_ResponseCode'];
$vnp_TransactionStatus = $inputData['vnp_TransactionStatus'];

try {
    if ($secureHash == $vnp_SecureHash) {
        $sql = "SELECT o.*, p.amount as payment_amount 
                FROM orders o 
                JOIN payments p ON o.order_id = p.order_id 
                WHERE o.order_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $vnp_TxnRef);
        $stmt->execute();
        $result = $stmt->get_result();
        $order = $result->fetch_assoc();

        if ($order) {
            if ($order["payment_amount"] == $vnp_Amount) {
                if ($order["status"] == 'Pending') {
                    if ($vnp_ResponseCode == '00' && $vnp_TransactionStatus == '00') {
                        // Thanh toán thành công
                        $new_status = 'Processing';
                        $payment_status = 'Paid';
                        
                        // Cập nhật đơn hàng
                        $update_order = "UPDATE orders SET status = ? WHERE order_id = ?";
                        $stmt = $conn->prepare($update_order);
                        $stmt->bind_param("ss", $new_status, $vnp_TxnRef);
                        $stmt->execute();

                        // Cập nhật payment
                        $update_payment = "UPDATE payments 
                                         SET status = ?,
                                             transaction_no = ?,
                                             bank_code = ?,
                                             payment_info = ?
                                         WHERE order_id = ?";
                        $stmt = $conn->prepare($update_payment);
                        $stmt->bind_param("sssss",
                            $payment_status,
                            $inputData['vnp_TransactionNo'],
                            $inputData['vnp_BankCode'], 
                            $inputData['vnp_OrderInfo'],
                            $vnp_TxnRef
                        );
                        $stmt->execute();

                        $isSuccess = true;
                    }
                }
            }
        }
    }
} catch (Exception $e) {
    error_log("VNPAY Return Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Kết quả thanh toán VNPAY</title>
        <!-- Bootstrap 5 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
        <style>
            .payment-status {
                text-align: center;
                padding: 40px 0;
            }
            .payment-status i {
                font-size: 64px;
                margin-bottom: 20px;
            }
            .success-icon {
                color: #198754;
            }
            .failed-icon {
                color: #dc3545;
            }
            .payment-details {
                background-color: #f8f9fa;
                border-radius: 10px;
                padding: 20px;
            }
            .form-group {
                margin-bottom: 1rem;
            }
        </style>
    </head>
    <body class="bg-light">
        <div class="container py-5">
            <!-- Payment Status -->
            <div class="payment-status">
                <?php if ($isSuccess): ?>
                    <i class="fas fa-check-circle success-icon"></i>
                    <h2 class="mb-3">Thanh toán thành công</h2>
                    <p class="text-muted">Cảm ơn bạn đã thanh toán. Đơn hàng của bạn đang được xử lý.</p>
                <?php else: ?>
                    <i class="fas fa-times-circle failed-icon"></i>
                    <h2 class="mb-3">Thanh toán không thành công</h2>
                    <p class="text-muted">Đã có lỗi xảy ra trong quá trình thanh toán. Vui lòng thử lại.</p>
                <?php endif; ?>
            </div>

            <!-- Payment Details -->
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Chi tiết giao dịch</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label"><i class="fas fa-shopping-cart me-2"></i>Mã đơn hàng</label>
                                        <input type="text" class="form-control" value="<?php echo $_GET['vnp_TxnRef'] ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label"><i class="fas fa-money-bill-wave me-2"></i>Số tiền</label>
                                        <input type="text" class="form-control" value="<?php echo number_format($_GET['vnp_Amount']/100, 0, ',', '.') ?> VNĐ" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label"><i class="fas fa-info-circle me-2"></i>Nội dung thanh toán</label>
                                <textarea class="form-control" rows="2" readonly><?php echo $_GET['vnp_OrderInfo'] ?></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label"><i class="fas fa-university me-2"></i>Ngân hàng</label>
                                        <input type="text" class="form-control" value="<?php echo $_GET['vnp_BankCode'] ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label"><i class="fas fa-clock me-2"></i>Thời gian thanh toán</label>
                                        <input type="text" class="form-control" value="<?php echo date('d/m/Y H:i:s', strtotime($_GET['vnp_PayDate'])) ?>" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <label class="form-label"><i class="fas fa-receipt me-2"></i>Mã giao dịch VNPAY</label>
                                <input type="text" class="form-control" value="<?php echo $_GET['vnp_TransactionNo'] ?>" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="text-center mt-4">
                        <a href="../index.php" class="btn btn-primary me-2">
                            <i class="fas fa-home me-2"></i>Về trang chủ
                        </a>
                        <a href="../order-info.php" class="btn btn-outline-primary">
                            <i class="fas fa-history me-2"></i>Xem lịch sử đơn hàng
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        
        <?php if ($isSuccess): ?>
        <script>
            // Thực thi ngay lập tức
            (function() {
                try {
                    // Xóa giỏ hàng
                    localStorage.clear(); // Xóa tất cả
                    // Hoặc xóa từng item
                    localStorage.removeItem('cartItems');
                    localStorage.removeItem('totalQuantity');
                    localStorage.removeItem('totalAmount');
                    
                    console.log('Cart cleared successfully');
                    
                    // Reload trang chủ sau 3 giây
                    setTimeout(function() {
                        window.location.href = '../index.php';
                    }, 3000);
                    
                } catch (e) {
                    console.error('Error clearing cart:', e);
                }
            })();
        </script>
        <?php endif; ?>
    </body>
</html>
