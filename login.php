<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }
        .login-container {
            margin-top: 5%;
            margin-bottom: 5%;
        }
        .login-form {
            padding: 5%;
            box-shadow: 0 5px 8px 0 rgba(0, 0, 0, 0.2), 0 9px 26px 0 rgba(0, 0, 0, 0.19);
            background-color: #fff;
            border-radius: 10px;
        }
        .login-form h3 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        .form-group {
            position: relative;
        }
        .form-group i {
            position: absolute;
            right: 15px;
            top: 42px;
            color: #999;
        }
        .login-container form {
            padding: 10%;
        }
        .btnSubmit {
            width: 100%;
            border-radius: 25px;
            padding: 1.5%;
            border: none;
            cursor: pointer;
            margin-top: 20px;
        }
        .login-form .btnSubmit {
            background-color: #0062cc;
            color: #fff;
        }
        .login-form .ForgetPwd {
            color: #0062cc;
            font-weight: 600;
            text-decoration: none;
        }
        .register-link {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container login-container">
        <div class="row justify-content-center">
            <div class="col-md-6 login-form">
                <h3>Đăng nhập</h3>
                <form action="login_form/process_login.php" method="POST">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="form-group">
                        <label for="password">Mật khẩu</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="remember">
                            <label class="custom-control-label" for="remember">Ghi nhớ đăng nhập</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btnSubmit">Đăng nhập</button>
                    
                    <div class="form-group register-link">
                        <span>Chưa có tài khoản? </span>
                        <a href="register.php">Đăng ký ngay</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <script>
        // Hiển thị/ẩn mật khẩu
        $(document).ready(function() {
            $('.fa-lock').click(function() {
                const passwordInput = $('#password');
                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    $(this).removeClass('fa-lock').addClass('fa-lock-open');
                } else {
                    passwordInput.attr('type', 'password');
                    $(this).removeClass('fa-lock-open').addClass('fa-lock');
                }
            });
        });
    </script>
</body>
</html>