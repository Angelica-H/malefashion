<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khôi phục mật khẩu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h3 class="card-title text-center mb-4">Khôi phục mật khẩu</h3>
                        <?php
                        if (isset($_GET['error']) && $_GET['error'] == '1') {
                            echo '<div class="alert alert-danger" role="alert">
                                    Email không tồn tại trong hệ thống. Vui lòng kiểm tra lại.
                                  </div>';
                        }
                        if (isset($_GET['error']) && $_GET['error'] == 'invalid_token') {
                            echo '<div class="alert alert-warning" role="alert">
                                    Token không hợp lệ hoặc đã hết hạn. Vui lòng nhập email để lấy token mới.
                                  </div>';
                        }
                        ?>
                        <form action="process/process_recover_password.php" method="POST" class="needs-validation" novalidate>
                            <div class="form-group mb-3">
                                <label for="email" class="form-label">Địa chỉ email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email" required placeholder="Nhập địa chỉ email của bạn">
                                    <div class="invalid-feedback">
                                        Vui lòng nhập một địa chỉ email hợp lệ.
                                    </div>
                                </div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">Yêu cầu đặt lại mật khẩu</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // JavaScript để vô hiệu hóa gửi biểu mẫu nếu có trường không hợp lệ
    (function () {
      'use strict'

      var forms = document.querySelectorAll('.needs-validation')

      Array.prototype.slice.call(forms)
        .forEach(function (form) {
          form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
              event.preventDefault()
              event.stopPropagation()
            }

            form.classList.add('was-validated')
          }, false)
        })
    })()
    </script>
</body>
</html>
