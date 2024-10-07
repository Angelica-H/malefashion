
<header class="header">
<div class="header__top">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-7">
                    <div class="header__top__left">
                        <p>Free shipping, 30-day return or refund guarantee.</p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-5">
                    <div class="header__top__right">
                        <div class="header__top__links">
                        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                              <a href="./account_details.php"> <?php echo htmlspecialchars($_SESSION['first_name']) . ' ' . htmlspecialchars($_SESSION['last_name']); ?></a>
                                         <a href="logout.php">Đăng xuất</a>
                                        <?php else: ?>
                             <button type="button" class="btn btn-primary" id="loginButton">Đăng Nhập</button>
                                 <a href="../register.php">Đăng ký</a>
                             <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3">
                <div class="header__logo">
                    <a href="./index.php"><img src="img/logo.png" alt=""></a>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <nav class="header__menu mobile-menu">
                    <ul>
                        <li class="active"><a href="./index.php">Home</a></li>
                        <li><a href="./shop.php">Shop</a></li>
                        <li><a href="#">Pages</a>
                            <ul class="dropdown">
                                <li><a href="./about.php">About Us</a></li>
                                <li><a href="./account_details.php">Tài khoản</a></li>
                                <li><a href="./shopping-cart.php">Giỏ hàng</a></li>
                                <li><a href="./checkout.php">Thanh toán</a></li>
                                <li><a href="./blog-details.php">Blog Details</a></li>
                            </ul>
                        </li>
                        <li><a href="./blog.php">Blog</a></li>
                        <li><a href="./contact.php">Contacts</a></li>
                    </ul>
                </nav>
            </div>
            <div class="col-lg-3 col-md-3">
            <div class="header__nav__option">
                <a href="#" class="search-switch"><img src="img/icon/search.png" alt=""></a>
                <a href="#"><img src="img/icon/heart.png" alt=""></a>
                <a href="./shopping-cart.php"><img src="img/icon/cart.png" alt="">
                    <span id="cart-count">0</span>
                </a>
                
                <div class="price" id="cart-total">$0.00</div>
</div>
            </div>
        </div>
    </div>
</header>

<!-- Modal đăng nhập -->
<div class="modal" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Đăng Nhập</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="loginForm" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mật Khẩu</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Đăng Nhập</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Liên kết Bootstrap và JavaScript -->
<script src="assets/js/cart.js"></script>    
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- JavaScript để xử lý modal và form -->
<script>
    // Khi người dùng nhấn nút "Đăng nhập", modal sẽ hiển thị
    document.getElementById('loginButton').addEventListener('click', function() {
        var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
        loginModal.show();
    });

  // Xử lý form đăng nhập
document.getElementById('loginForm').addEventListener('submit', function(event) {
    //event.preventDefault(); // Ngăn form tải lại trang
    var email = document.getElementById('email').value;
    var password = document.getElementById('password').value;

    // Gửi dữ liệu đăng nhập tới backend
    fetch('login_form/process_customer_login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
    })
    .then(response => response.json())  // Chuyển phản hồi thành JSON
    .then(data => {
        if (data.status === 'success') {
            // Đăng nhập thành công, đóng modal đăng nhập
            var loginModal = bootstrap.Modal.getInstance(document.getElementById('loginModal'));
            loginModal.hide();
           
            // Cập nhật giao diện: hiển thị tên người dùng và nút đăng xuất
            document.querySelector('.header__top__links').innerHTML = `
                <span>Xin chào, ${data.first_name} ${data.last_name}</span>
                <a href="logout.php">Đăng xuất</a>
            `;
        } else {
            // Hiển thị thông báo lỗi nếu đăng nhập không thành công
            alert(data.message); // Hiển thị thông báo lỗi từ PHP
        }
    })
    .catch(error => console.error('Error:', error));
});


</script>
<script src="assets/js/cart.js"></script>