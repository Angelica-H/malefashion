<?php
include 'includes/db_connect.php'; // Kết nối cơ sở dữ liệu
session_start();


?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Male_Fashion Template">
    <meta name="keywords" content="Male_Fashion, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Male-Fashion | Template</title>

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

    <!-- Hero Section Begin -->
    <section class="hero">
        <div class="hero__slider owl-carousel">
            <div class="hero__items set-bg" data-setbg="assets/img/hero/hero-1.jpg">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-5 col-lg-7 col-md-8">
                            <div class="hero__text">
                                <h6>Bộ sưu tập mùa hè</h6>
                                <h2>Bộ sưu tập Thu – Đông 2030</h2>
                                <p>Một nhãn hiệu chuyên tạo ra những sản phẩm thiết yếu sang trọng. Được tạo ra một cách có đạo đức với một thái độ kiên định
                                cam kết chất lượng vượt trội.</p>
                                <a href="shop.php" class="primary-btn">Mua sắm ngay bây giờ <span class="arrow_right"></span></a>
                                <div class="hero__social">
                                    <a href="#"><i class="fa fa-facebook"></i></a>
                                    <a href="#"><i class="fa fa-twitter"></i></a>
                                    <a href="#"><i class="fa fa-pinterest"></i></a>
                                    <a href="#"><i class="fa fa-instagram"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hero__items set-bg" data-setbg="assets/img/hero/hero-2.jpg">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-5 col-lg-7 col-md-8">
                            <div class="hero__text">
                                <h6>Bộ sưu tập mùa hè</h6>
                                <h2>Bộ sưu tập Thu – Đông 2024s</h2>
                                <p>Một nhãn hiệu chuyên tạo ra những sản phẩm thiết yếu sang trọng. Được tạo ra một cách có đạo đức với một thái độ kiên định
                                cam kết chất lượng vượt trội.</p>
                                <a href="#" class="primary-btn">Mua sắm ngay  <span class="arrow_right"></span></a>
                                <div class="hero__social">
                                    <a href="#"><i class="fa fa-facebook"></i></a>
                                    <a href="#"><i class="fa fa-twitter"></i></a>
                                    <a href="#"><i class="fa fa-pinterest"></i></a>
                                    <a href="#"><i class="fa fa-instagram"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Hero Section End -->

    <!-- Banner Section Begin -->
    <section class="banner spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 offset-lg-4">
                    <div class="banner__item">
                        <div class="banner__item__pic">
                            <img src="img/banner/banner-1.jpg" alt="">
                        </div>
                        <div class="banner__item__text">
                            <h2>Bộ sưu tập lothing 2024</h2>
                            <a href="#">Mua ngay</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="banner__item banner__item--middle">
                        <div class="banner__item__pic">
                            <img src="assets/img/banner/banner-2.jpg" alt="">
                        </div>
                        <div class="banner__item__text">
                            <h2>Phụ kiện</h2>
                            <a href="#">Mua ngay</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="banner__item banner__item--last">
                        <div class="banner__item__pic">
                            <img src="assets/img/banner/banner-3.jpg" alt="">
                        </div>
                        <div class="banner__item__text">
                            <h2>Giày Xuân 2024</h2>
                            <a href="#">Mua ngay</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Banner Section End -->

    <!-- Product Section Begin -->
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <ul class="filter__controls">
                    <li class="active" data-filter="*" style="display: none;">Tất cả</li>
                    <li data-filter=".best-seller">Best Sellers</li>
                    <li data-filter=".new-arrivals">New Arrivals</li>
                    <li data-filter=".hot-sales">Hot Sales</li>
                </ul>
            </div>
        </div>
        <div class="row product__filter">
        <?php
include 'includes/db_connect.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Lấy tất cả sản phẩm từ cơ sở dữ liệu
$sql_all_products = "SELECT p.*, b.brand_name, c.category_name,
                     GROUP_CONCAT(DISTINCT s.size_name) AS sizes,
                     GROUP_CONCAT(DISTINCT cl.color_name) AS colors
                     FROM products p
                     LEFT JOIN brands b ON p.brand_id = b.brand_id
                     LEFT JOIN categories c ON p.category_id = c.category_id
                     LEFT JOIN product_variants pv ON p.product_id = pv.product_id
                     LEFT JOIN sizes s ON pv.size_id = s.size_id
                     LEFT JOIN colors cl ON pv.color_id = cl.color_id
                     GROUP BY p.product_id
                     ORDER BY p.created_at DESC";

$result_all_products = $conn->query($sql_all_products);

if ($result_all_products === false) {
    echo "Lỗi truy vấn: " . $conn->error;
} elseif ($result_all_products->num_rows > 0) {
    while ($product = $result_all_products->fetch_assoc()) {
        // Xác định các lớp CSS cho việc lọc
        $filterClasses = [];
        if ($product['is_best_seller']) $filterClasses[] = 'best-seller';
        if ($product['is_new_arrival']) $filterClasses[] = 'new-arrivals';
        if ($product['is_hot_sale']) $filterClasses[] = 'hot-sales';
        $filterClassString = implode(' ', $filterClasses);
        ?>
        <div class="col-lg-3 col-md-6 col-sm-6 mix <?php echo $filterClassString; ?>">
            <div class="product__item">
                <div class="product__item__pic set-bg"
                    data-setbg="<?php echo htmlspecialchars($product['product_image']); ?>">
                    <?php if ($product['is_new_arrival']): ?>
                        <span class="label">New</span>
                    <?php elseif ($product['is_hot_sale']): ?>
                        <span class="label">Hot Sale</span>
                    <?php endif; ?>
                    <ul class="product__hover">
                        <li><a href="#"><img src="assets/img/icon/heart.png" alt=""></a></li>
                        <li><a href="#"><img src="assets/img/icon/compare.png" alt=""> <span>Compare</span></a></li>
                        <li><a href="shop-details.php?id=<?php echo $product['product_id']; ?>"><img src="assets/img/icon/search.png" alt=""></a></li>
                    </ul>
                </div>
                <div class="product__item__text">
                    <h6><?php echo htmlspecialchars($product['product_name']); ?></h6>
                    <a href="shop-details.php?id=<?php echo $product['product_id']; ?>" class="add-cart">
                        Xem chi tiết sản phẩm
                    </a>
                    <div class="rating">
                        <i class="fa fa-star-o"></i>
                        <i class="fa fa-star-o"></i>
                        <i class="fa fa-star-o"></i>
                        <i class="fa fa-star-o"></i>
                        <i class="fa fa-star-o"></i>
                    </div>
                    <?php if ($product['sale_price']): ?>
                        <h5 style="text-decoration: line-through;"><?php echo number_format($product['price']); ?>đ</h5>
                        <h5 class="sale-price" style="color: red;"><?php echo number_format($product['sale_price']); ?>đ</h5>
                    <?php else: ?>
                        <h5><?php echo number_format($product['price']); ?>đ</h5>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }
} else {
    echo "<p>Không có sản phẩm nào.</p>";
}
?>
    </div>
    </div>
    </div>

    </section>
    <!-- Product Section End -->

    <!-- Categories Section Begin -->
    <section class="categories spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="categories__text">
                        <h2>Clothings Hot <br /> <span>Shoe Collection</span> <br /> Accessories</h2>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="categories__hot__deal">
                        <img src="assets/img/product-sale.png" alt="">
                        <div class="hot__deal__sticker">
                            <span>Sale Of</span>
                            <h5>$29.99</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 offset-lg-1">
                    <div class="categories__deal__countdown">
                        <span>Giao dịch trong tuần</span>
                        <h2>Túi đeo chéo nhiều ngăn màu đen</h2>
                        <div class="categories__deal__countdown__timer" id="countdown">
                            <div class="cd-item">
                                <span>3</span>
                                <p>Days</p>
                            </div>
                            <div class="cd-item">
                                <span>1</span>
                                <p>Hours</p>
                            </div>
                            <div class="cd-item">
                                <span>50</span>
                                <p>Minutes</p>
                            </div>
                            <div class="cd-item">
                                <span>18</span>
                                <p>Seconds</p>
                            </div>
                        </div>
                        <a href="#" class="primary-btn">Mua ngay</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Categories Section End -->

    <!-- Instagram Section Begin -->
    <section class="instagram spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="instagram__pic">
                        <div class="instagram__pic__item set-bg" data-setbg="assets/img/instagram/instagram-1.jpg"></div>
                        <div class="instagram__pic__item set-bg" data-setbg="assets/img/instagram/instagram-2.jpg"></div>
                        <div class="instagram__pic__item set-bg" data-setbg="assets/img/instagram/instagram-3.jpg"></div>
                        <div class="instagram__pic__item set-bg" data-setbg="assets/img/instagram/instagram-4.jpg"></div>
                        <div class="instagram__pic__item set-bg" data-setbg="assets/img/instagram/instagram-5.jpg"></div>
                        <div class="instagram__pic__item set-bg" data-setbg="assets/img/instagram/instagram-6.jpg"></div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="instagram__text">
                        <h2>Instagram</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                            labore et dolore magna aliqua.</p>
                        <h3>#Male_Fashion</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Instagram Section End -->

    <!-- Latest Blog Section Begin -->
    <section class="latest spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <span>Latest News</span>
                        <h2>Fashion New Trends</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="blog__item">
                        <div class="blog__item__pic set-bg" data-setbg="assets/img/blog/blog-1.jpg"></div>
                        <div class="blog__item__text">
                            <span><img src="assets/img/icon/calendar.png" alt=""> 16 February 2020</span>
                            <h5>What Curling Irons Are The Best Ones</h5>
                            <a href="#">Read More</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="blog__item">
                        <div class="blog__item__pic set-bg" data-setbg="assets/img/blog/blog-2.jpg"></div>
                        <div class="blog__item__text">
                            <span><img src="assets/img/icon/calendar.png" alt=""> 21 February 2020</span>
                            <h5>Eternity Bands Do Last Forever</h5>
                            <a href="#">Read More</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="blog__item">
                        <div class="blog__item__pic set-bg" data-setbg="assets/img/blog/blog-3.jpg"></div>
                        <div class="blog__item__text">
                            <span><img src="assets/img/icon/calendar.png" alt=""> 28 February 2020</span>
                            <h5>The Health Benefits Of Sunglasses</h5>
                            <a href="#">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Latest Blog Section End -->

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
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    function setActiveColor(selectedInput, productId) {
        const colorLabels = document.querySelectorAll(`.product__color__select[data-product-id="${productId}"] label`);

        colorLabels.forEach(label => label.classList.remove('active'));

        selectedInput.parentElement.classList.add('active');
    }
</script>


</html>