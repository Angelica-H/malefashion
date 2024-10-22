<?php include "includes/db_connect.php";
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
    <title>Male-Fashion | Mẫu</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&display=swap"
    rel="stylesheet">

    <!-- Css Styles -->
    <?php include "includes/css.php" ?>

</head>

<body>
    <!-- Tải trang -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Menu ngoài lề Bắt đầu -->
    <?php  include "includes/menu_begin.php"
    ?>
    <!-- Menu ngoài lề Kết thúc -->

    <!-- Phần Header Bắt đầu -->
    <?php include "includes/header_section.php" ?>
    <!-- Phần Header Kết thúc -->

    <!-- Phần Breadcrumb Bắt đầu -->
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Về Chúng Tôi</h4>
                        <div class="breadcrumb__links">
                            <a href="./index.html">Trang chủ</a>
                            <span>Về Chúng Tôi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Phần Breadcrumb Kết thúc -->

    <!-- Phần Giới Thiệu Bắt đầu -->
    <section class="about spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="about__pic">
                        <img src="./assets/img/about/about-us.jpg" alt="">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="about__item">
                        <h4>Chúng Tôi Là Ai?</h4>
                        <p>Các chương trình quảng cáo theo ngữ cảnh đôi khi có những chính sách nghiêm ngặt cần phải tuân thủ.
                        Hãy lấy Google làm ví dụ.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="about__item">
                        <h4>Chúng Tôi Làm Gì?</h4>
                        <p>Trong thế hệ kỹ thuật số này, nơi thông tin có thể dễ dàng thu được trong vài giây, danh thiếp
                        vẫn giữ được tầm quan trọng của chúng.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="about__item">
                        <h4>Tại Sao Chọn Chúng Tôi</h4>
                        <p>Một ngôi nhà hai hoặc ba tầng là cách lý tưởng để tối đa hóa mảnh đất mà ngôi nhà của chúng ta
                        tọa lạc, nhưng đối với người già hoặc người khuyết tật.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Phần Giới Thiệu Kết thúc -->

    <!-- Phần Đánh Giá Bắt đầu -->
    <section class="testimonial">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 p-0">
                    <div class="testimonial__text">
                        <span class="icon_quotations"></span>
                        <p>"Đi chơi sau giờ làm? Hãy mang theo máy uốn tóc butane đến văn phòng, làm nóng nó,
                            tạo kiểu tóc trước khi rời văn phòng và bạn sẽ không phải quay lại nhà."
                        </p>
                        <div class="testimonial__author">
                            <div class="testimonial__author__pic">
                                <img src="./assets/img/about/testimonial-author.jpg" alt="">
                            </div>
                            <div class="testimonial__author__text">
                                <h5>Augusta Schultz</h5>
                                <p>Thiết Kế Thời Trang</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 p-0">
                    <div class="testimonial__pic set-bg" data-setbg="./assets/img/about/testimonial-pic.jpg"></div>
                </div>
            </div>
        </div>
    </section>
    <!-- Phần Đánh Giá Kết thúc -->

    <!-- Phần Đếm Số Bắt đầu -->
    <section class="counter spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="counter__item">
                        <div class="counter__item__number">
                            <h2 class="cn_num">102</h2>
                        </div>
                        <span>Khách hàng <br />của chúng tôi</span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="counter__item">
                        <div class="counter__item__number">
                            <h2 class="cn_num">30</h2>
                        </div>
                        <span>Tổng số <br />Danh mục</span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="counter__item">
                        <div class="counter__item__number">
                            <h2 class="cn_num">102</h2>
                        </div>
                        <span>Trong <br />Quốc gia</span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="counter__item">
                        <div class="counter__item__number">
                            <h2 class="cn_num">98</h2>
                            <strong>%</strong>
                        </div>
                        <span>Khách hàng <br />Hài lòng</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Phần Đếm Số Kết thúc -->

    <!-- Phần Đội Ngũ Bắt đầu -->
    <section class="team spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <span>Đội ngũ của chúng tôi</span>
                        <h2>Gặp gỡ đội ngũ của chúng tôi</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="team__item">
                        <img src="./assets/img/about/team-1.jpg" alt="">
                        <h4>John Smith</h4>
                        <span>Thiết kế thời trang</span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="team__item">
                        <img src="./assets/img/about/team-2.jpg" alt="">
                        <h4>Christine Wise</h4>
                        <span>Giám đốc điều hành</span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="team__item">
                        <img src="./assets/img/about/team-3.jpg" alt="">
                        <h4>Sean Robbins</h4>
                        <span>Quản lý</span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="team__item">
                        <img src="./assets/img/about/team-4.jpg" alt="">
                        <h4>Lucy Myers</h4>
                        <span>Giao hàng</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Phần Đội Ngũ Kết thúc -->

    <!-- Phần Khách Hàng Bắt đầu -->
    <section class="clients spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <span>Đối tác</span>
                        <h2>Khách hàng hài lòng</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-4 col-6">
                    <a href="#" class="client__item"><img src="./assets/img/clients/client-1.png" alt=""></a>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-4 col-6">
                    <a href="#" class="client__item"><img src="./assets/img/clients/client-2.png" alt=""></a>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-4 col-6">
                    <a href="#" class="client__item"><img src="./assets/img/clients/client-3.png" alt=""></a>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-4 col-6">
                    <a href="#" class="client__item"><img src="./assets/img/clients/client-4.png" alt=""></a>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-4 col-6">
                    <a href="#" class="client__item"><img src="./assets/img/clients/client-5.png" alt=""></a>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-4 col-6">
                    <a href="#" class="client__item"><img src="./assets/img/clients/client-6.png" alt=""></a>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-4 col-6">
                    <a href="#" class="client__item"><img src="./assets/img/clients/client-7.png" alt=""></a>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-4 col-6">
                    <a href="#" class="client__item"><img src="./assets/img/clients/client-8.png" alt=""></a>
                </div>
            </div>
        </div>
    </section>
    <!-- Phần Khách Hàng Kết thúc -->

    <!-- Phần Footer Bắt đầu -->
    <?php include "includes/footer_section.php" ?>
    <!-- Phần Footer Kết thúc -->

    <!-- Tìm Kiếm Bắt đầu -->
    <div class="search-model">
        <div class="h-100 d-flex align-items-center justify-content-center">
            <div class="search-close-switch">+</div>
            <form class="search-model-form">
                <input type="text" id="search-input" placeholder="Tìm kiếm ở đây.....">
            </form>
        </div>
    </div>
    <!-- Tìm Kiếm Kết thúc -->

    <!-- Js Plugins -->
    <?php include "includes/js.php" ?>

</body>

</html>