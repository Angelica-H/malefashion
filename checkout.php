<?php
include "includes/db_connect.php";
session_start();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Male_Fashion Template">
    <meta name="keywords" content="Male_Fashion, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Thanh toán - Male-Fashion</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&display=swap"
    rel="stylesheet">

    <!-- Css Styles -->
    <?php include "includes/css.php" ?>
</head>

<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Offcanvas Menu Begin -->
    <?php include "includes/menu_begin.php" ?>
    <!-- Offcanvas Menu End -->

    <!-- Header Section Begin -->
    <?php include "includes/header_section.php" ?>
    <!-- Header Section End -->

    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Thanh toán</h4>
                        <div class="breadcrumb__links">
                            <a href="./index.php">Trang chủ</a>
                            <a href="./shop.php">Cửa hàng</a>
                            <span>Thanh toán</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Checkout Section Begin -->
    <section class="checkout spad">
        <div class="container">
            <div class="checkout__form">
                <form id="checkout-form" action="includes/process_order.php" method="POST">
                    <div class="row">
                        <div class="col-lg-8 col-md-6">
                            <h6 class="checkout__title">Thông tin thanh toán</h6>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>Họ<span>*</span></p>
                                        <input type="text" name="last_name" value="<?php echo isset($_SESSION['last_name']) ? htmlspecialchars($_SESSION['last_name']) : ''; ?>" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>Tên<span>*</span></p>
                                        <input type="text" name="first_name" value="<?php echo isset($_SESSION['first_name']) ? htmlspecialchars($_SESSION['first_name']) : ''; ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="checkout__input">
                                <p>Địa chỉ<span>*</span></p>
                                <input type="text" name="shipping_address" value="<?php echo isset($_SESSION['shipping_address']) ? htmlspecialchars($_SESSION['shipping_address']) : ''; ?>" placeholder="Địa chỉ giao hàng" class="checkout__input__add" required>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>Số điện thoại<span>*</span></p>
                                        <input type="text" name="phone_number" value="<?php echo isset($_SESSION['phone_number']) ? htmlspecialchars($_SESSION['phone_number']) : ''; ?>" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>Email<span>*</span></p>
                                        <input type="email" name="email" value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?>" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="checkout__order">
                                <h4 class="order__title">Đơn hàng của bạn</h4>
                                <div class="checkout__order__products">Sản phẩm <span>Tổng</span></div>
                                <ul class="checkout__total__products" id="cart-items">
                                    <!-- Các mục giỏ hàng sẽ được thêm vào đây bằng JavaScript -->
                                </ul>
                                <ul class="checkout__total__all">
                                    <li>Tổng phụ <span id="subtotal">0 đ</span></li>
                                    <li>Phí vận chuyển <span id="shipping-fee">30,000 đ</span></li>
                                    <li>Tổng cộng <span id="total-amount">0 đ</span></li>
                                </ul>
                                <div class="checkout__input__checkbox">
                                    <label for="payment">
                                            <label>Phương thức thanh toán</label>
                                        <select name="payment_method" required>
                                            <option value="COD">Thanh toán khi nhận hàng</option>
                                            <option value="VNPAY">Chuyển khoản ngân hàng</option>
                                        </select>
                                    </label>
                                </div>
                                <input type="hidden" name="total_amount" id="total_amount" value="0">
                                <input type="hidden" name="cart_data" id="cart_data" value="">
                                <button type="submit" class="site-btn" id="checkout-button">ĐẶT HÀNG</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- Checkout Section End -->

    <!-- Footer Section Begin -->
    <?php include "includes/footer_section.php" ?>
    <!-- Footer Section End -->

    <!-- Search Begin -->
    <div class="search-model">
        <div class="h-100 d-flex align-items-center justify-content-center">
            <div class="search-close-switch">+</div>
            <form class="search-model-form">
                <input type="text" id="search-input" placeholder="Search here.....">
            </form>
        </div>
    </div>
    <!-- Search End -->

    <!-- Js Plugins -->
    <?php include "includes/js.php" ?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        const cartItemsContainer = document.getElementById('cart-items');
        const subtotalElement = document.getElementById('subtotal');
        const totalAmountElement = document.getElementById('total-amount');
        const totalAmountInput = document.getElementById('total_amount');
        const cartDataInput = document.getElementById('cart_data');
        const checkoutButton = document.getElementById('checkout-button');

        if (cart.length === 0) {
            alert('Giỏ hàng của bạn đang trống. Vui lòng thêm sản phẩm vào giỏ hàng trước khi thanh toán.');
            window.location.href = 'shop.php';
            return;
        }

        let subtotal = 0;

        cart.forEach((item, index) => {
            const li = document.createElement('li');
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;
            li.textContent = `${item.name} x ${item.quantity} `;
            li.innerHTML += `<span>${itemTotal.toLocaleString('vi-VN')} đ</span>`;
            cartItemsContainer.appendChild(li);
        });

        const shippingFee = 30000;
        const total = subtotal + shippingFee;
        subtotalElement.textContent = `${subtotal.toLocaleString('vi-VN')} đ`;
        totalAmountElement.textContent = `${total.toLocaleString('vi-VN')} đ`;
        totalAmountInput.value = total;
        cartDataInput.value = JSON.stringify(cart);

        document.getElementById('checkout-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!<?php echo isset($_SESSION['customer_id']) ? 'true' : 'false'; ?>) {
                alert('Vui lòng đăng nhập để tiếp tục thanh toán.');
                return;
            }

            if (cart.length === 0) {
                alert('Giỏ hàng của bạn đang trống. Vui lòng thêm sản phẩm vào giỏ hàng trước khi thanh toán.');
                window.location.href = 'shop.php';
                return;
            }

            const formData = new FormData(this);
            fetch('includes/process_order.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    if (data.payment_method === 'VNPAY') {
                        const vnpayForm = document.createElement('form');
                        vnpayForm.method = 'POST';  // Sử dụng POST thay vì GET
                        vnpayForm.action = data.redirect_url;
                        
                        // Các tham số bắt buộc
                        const params = {
                            'order_id': data.order_id,        // Mã đơn hàng
                            'amount': data.amount,            // Số tiền thanh toán
                            'order_desc': 'Thanh toan don hang ' + data.order_id,  // Mô tả đơn hàng
                            'bank_code': '',                  // Mã ngân hàng (có thể để trống)
                            'language': 'vn'                  // Ngôn ngữ hiển thị
                        };
                        
                        // Tạo các input fields
                        for (let key in params) {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = key;
                            input.value = params[key];
                            vnpayForm.appendChild(input);
                        }
                        
                        document.body.appendChild(vnpayForm);
                        vnpayForm.submit();
                    } else {
                        localStorage.removeItem('cart');
                        alert(data.message);
                        window.location.href = 'order_success.php?order_id=' + data.order_id;
                    }
                } else {
                    alert(data.message || 'Có lỗi xảy ra khi xử lý đơn hàng');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi xử lý đơn hàng');
            });
        });
    });
    </script>
</body>

</html>
