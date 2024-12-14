<?php
require_once("./config.php");
require_once("../includes/db_connect.php"); // Thêm kết nối database
header('Access-Control-Allow-Origin: *');
// Khởi tạo dữ liệu input và output
$inputData = array();
$returnData = array();

// Lấy dữ liệu từ VNPAY
foreach ($_GET as $key => $value) {
    if (substr($key, 0, 4) == "vnp_") {
        $inputData[$key] = $value;
    }
}

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
$vnp_TxnRef = $inputData['vnp_TxnRef']; // Mã đơn hàng
$vnp_Amount = $inputData['vnp_Amount']/100; // Số tiền
$vnp_ResponseCode = $inputData['vnp_ResponseCode']; // Mã phản hồi
$vnp_TransactionStatus = $inputData['vnp_TransactionStatus']; // Trạng thái giao dịch

try {
    // Kiểm tra chữ ký
    if ($secureHash == $vnp_SecureHash) {
        // Lấy thông tin đơn hàng từ database
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
            // Kiểm tra số tiền khớp với database
            if ($order["payment_amount"] == $vnp_Amount) {
                // Kiểm tra trạng thái đơn hàng
                if ($order["status"] == 'Pending') {
                    // Cập nhật trạng thái đơn hàng
                    if ($vnp_ResponseCode == '00' && $vnp_TransactionStatus == '00') {
                        // Thanh toán thành công
                        $new_status = 'Processing';
                        $payment_status = 'Paid';
                        
                        // Cập nhật trạng thái đơn hàng
                        $update_order = "UPDATE orders SET status = ? WHERE order_id = ?";
                        $stmt = $conn->prepare($update_order);
                        $stmt->bind_param("ss", $new_status, $vnp_TxnRef);
                        $stmt->execute();

                        // Cập nhật payment với đầy đủ thông tin
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

                        // Log giao dịch thành công
                        error_log("VNPAY payment successful for order: " . $vnp_TxnRef);
                        
                        $returnData['RspCode'] = '00';
                        $returnData['Message'] = 'Confirm Success';
                    } else {
                        // Thanh toán thất bại
                        $new_status = 'Failed';
                        $payment_status = 'Failed';
                        
                        // Cập nhật trạng thái đơn hàng
                        $update_order = "UPDATE orders SET status = ? WHERE order_id = ?";
                        $stmt = $conn->prepare($update_order);
                        $stmt->bind_param("ss", $new_status, $vnp_TxnRef);
                        $stmt->execute();

                        // Cập nhật payment với đầy đủ thông tin
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

                        // Log giao dịch thất bại
                        error_log("VNPAY payment failed for order: " . $vnp_TxnRef);
                        
                        $returnData['RspCode'] = '10';
                        $returnData['Message'] = 'Payment Failed';
                    }
                } else {
                    $returnData['RspCode'] = '02';
                    $returnData['Message'] = 'Order already processed';
                }
            } else {
                $returnData['RspCode'] = '04';
                $returnData['Message'] = 'Invalid amount';
            }
        } else {
            $returnData['RspCode'] = '01';
            $returnData['Message'] = 'Order not found';
        }
    } else {
        $returnData['RspCode'] = '97';
        $returnData['Message'] = 'Invalid signature';
    }
} catch (Exception $e) {
    error_log("VNPAY IPN Error: " . $e->getMessage());
    $returnData['RspCode'] = '99';
    $returnData['Message'] = 'Unknown error';
}

// Trả kết quả cho VNPAY
echo json_encode($returnData);
