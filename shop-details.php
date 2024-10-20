<?php
include 'includes/db_connect.php'; // Kết nối cơ sở dữ liệu

session_start();

// Lấy product_id từ URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Truy vấn để lấy thông tin sản phẩm
$productQuery = "SELECT p.*, c.category_name, b.brand_name 
                 FROM products p
                 LEFT JOIN categories c ON p.category_id = c.category_id
                 LEFT JOIN brands b ON p.brand_id = b.brand_id
                 WHERE p.product_id = ?";
$stmt = $conn->prepare($productQuery);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    die("Sản phẩm không tồn tại");
}

// Truy vấn để lấy các biến thể của sản phẩm
$variantQuery = "SELECT pv.*, s.size_name, c.color_name, c.color_code 
                 FROM product_variants pv
                 LEFT JOIN sizes s ON pv.size_id = s.size_id
                 LEFT JOIN colors c ON pv.color_id = c.color_id
                 WHERE pv.product_id = ?";
$stmt = $conn->prepare($variantQuery);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$variantResult = $stmt->get_result();
$variants = $variantResult->fetch_all(MYSQLI_ASSOC);

// Tạo mảng các kích thước và màu sắc duy nhất
$sizes = array_unique(array_column($variants, 'size_name'));
$colors = array_unique(array_column($variants, 'color_name'));


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
    <?php include "includes/menu_begin.php"
        ?>
    <!-- Offcanvas Menu End -->

    <!-- Header Section Begin -->
    <?php include "includes/header_section.php" ?>
    <!-- Header Section End -->

    <!-- Shop Details Section Begin -->
    <section class="shop-details">
        <div class="product__details__pic">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="product__details__breadcrumb">
                            <a href="./index.html">Home</a>
                            <a href="./shop.html">Shop</a>
                            <span>Product Details</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-3">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">
                                    <div class="product__thumb__pic set-bg"
                                        data-setbg="assets/img/product/<?php echo $product['product_image']; ?>">
                                    </div>
                                </a>
                            </li>
                            <!-- Thêm các ảnh khác nếu cần -->
                        </ul>
                    </div>
                    <div class="col-lg-6 col-md-9">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tabs-1" role="tabpanel">
                                <div class="product__details__pic__item">
                                    <img src="assets/img/product/<?php echo $product['product_image']; ?>" alt="">
                                </div>
                            </div>
                            <!-- Thêm các tab ảnh khác nếu cần -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="product__details__content">
            <div class="container">
                <div class="row d-flex justify-content-center">
                <div class="container">
    <div class="row">
        <div class="col-lg-6">
            <div class="product__details__pic">
                <div class="product__details__pic__item">
                    <img class="product__details__pic__item--large"
                        src="<?php echo htmlspecialchars($product['product_image']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                </div>
                <div class="product__details__pic__slider owl-carousel">
                    <?php foreach ($images as $image): ?>
                        <img data-imgbigurl="<?php echo htmlspecialchars($image['image_url']); ?>"
                            src="<?php echo htmlspecialchars($image['image_url']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="product__details__text">
                <h4><?php echo htmlspecialchars($product['product_name']); ?></h4>
                <div class="rating">
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star-o"></i>
                    <span> - 5 Reviews</span>
                </div>
                <h3><?php echo number_format($product['sale_price'], 0, ',', '.'); ?>đ
                    <?php if ($product['price'] > $product['sale_price']): ?>
                        <span><?php echo number_format($product['price'], 0, ',', '.'); ?>đ</span>
                    <?php endif; ?>
                </h3>
                <p><?php echo htmlspecialchars($product['description']); ?></p>
                <div class="product__details__option">
                    <div class="product__details__option__size">
                        <span>Kích thước:</span>
                        <?php foreach ($sizes as $size): ?>
                            <label for="<?php echo htmlspecialchars($size); ?>">
                                <input type="radio" id="<?php echo htmlspecialchars($size); ?>"
                                       name="size" value="<?php echo htmlspecialchars($size); ?>">
                                <?php echo htmlspecialchars($size); ?>
                            </label>
                        <?php endforeach; ?>
                    </div>

                    <div class="product__details__option__color">
                        <span>Màu sắc:</span>
                        <?php foreach ($colors as $index => $color): 
                            $colorCode = array_values(array_filter($variants, function($v) use ($color) {
                                return $v['color_name'] == $color;
                            }))[0]['color_code'];
                        ?>
                            <label class="c-<?php echo $index; ?>"
                                   for="color-<?php echo $index; ?>"
                                   style="background-color: <?php echo htmlspecialchars($colorCode); ?>">
                                <input type="radio" id="color-<?php echo $index; ?>"
                                       name="color"
                                       value="<?php echo htmlspecialchars($color); ?>"
                                       onclick="setActiveColor(this)">
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="product__details__cart__option">
                    <div class="quantity">
                        <div class="pro-qty">
                            <input type="text" value="1" id="quantity">
                        </div>
                    </div>
                    <a href="javascript:void(0)" class="primary-btn" onclick="addToCartFromData('<?php echo htmlspecialchars(json_encode([
                        'id' => $product['product_id'],
                        'name' => $product['product_name'],
                        'price' => $product['price'],
                        'sale_price' => $product['sale_price'],
                        'availableSizes' => $sizes,
                        'availableColors' => $colors,
                        'image' => 'assets/img/product/' . $product['product_image']
                    ])); ?>', document.querySelector('input[name=\'size\']:checked').value, document.querySelector('input[name=\'color\']:checked').value, document.getElementById('quantity').value)">
                        Thêm vào giỏ
                    </a>
                </div>
                <div class="product__details__btns__option">
                    <a href="#"><i class="fa fa-heart"></i> Thêm vào yêu thích</a>
                    <a href="#"><i class="fa fa-exchange"></i> So sánh</a>
                </div>
                <div class="product__details__last__option">
                    <h5><span>Guaranteed Safe Checkout</span></h5>
                    <img src="img/shop-details/details-payment.png" alt="">
                    <ul>
                        <li><span>Danh mục:</span> <?php echo htmlspecialchars($product['category_name']); ?></li>
                        <li><span>Thương hiệu:</span> <?php echo htmlspecialchars($product['brand_name']); ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="product__details__tab">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#tabs-5" role="tab">Mô Tả</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tabs-6" role="tab">Đánh Giá Khách
                                        Hàng</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tabs-7" role="tab">Thông Tin Thêm</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tabs-5" role="tabpanel">
                                    <div class="product__details__tab__content">
                                        <p class="note">Thông tin mô tả sản phẩm tại đây...</p>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tabs-6" role="tabpanel">
                                    <div class="product__details__tab__content">
                                        <p>Đánh giá từ khách hàng...</p>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tabs-7" role="tabpanel">
                                    <div class="product__details__tab__content">
                                        <p>Thông tin thêm về sản phẩm...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>

    <script>
        // JavaScript để thiết lập ảnh nền
        document.querySelectorAll('.set-bg').forEach(function (element) {
            var bg = element.getAttribute('data-setbg');
            element.style.backgroundImage = 'url(' + bg + ')';
        });
    </script>
    <!-- Shop Details Section End -->

    <!-- Related Section Begin -->
    <section class="related spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="related-title">Related Product</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-sm-6">
                    <div class="product__item">
                        <div class="product__item__pic set-bg" data-setbg="img/product/product-1.jpg">
                            <span class="label">New</span>
                            <ul class="product__hover">
                                <li><a href="#"><img src="img/icon/heart.png" alt=""></a></li>
                                <li><a href="#"><img src="img/icon/compare.png" alt=""> <span>Compare</span></a></li>
                                <li><a href="#"><img src="img/icon/search.png" alt=""></a></li>
                            </ul>
                        </div>
                        <div class="product__item__text">
                            <h6>Piqué Biker Jacket</h6>
                            <a href="#" class="add-cart">+ Add To Cart</a>
                            <div class="rating">
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                            </div>
                            <h5>$67.24</h5>
                            <div class="product__color__select">
                                <label for="pc-1">
                                    <input type="radio" id="pc-1">
                                </label>
                                <label class="active black" for="pc-2">
                                    <input type="radio" id="pc-2">
                                </label>
                                <label class="grey" for="pc-3">
                                    <input type="radio" id="pc-3">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-sm-6">
                    <div class="product__item">
                        <div class="product__item__pic set-bg" data-setbg="img/product/product-2.jpg">
                            <ul class="product__hover">
                                <li><a href="#"><img src="img/icon/heart.png" alt=""></a></li>
                                <li><a href="#"><img src="img/icon/compare.png" alt=""> <span>Compare</span></a></li>
                                <li><a href="#"><img src="img/icon/search.png" alt=""></a></li>
                            </ul>
                        </div>
                        <div class="product__item__text">
                            <h6>Piqué Biker Jacket</h6>
                            <a href="#" class="add-cart">+ Add To Cart</a>
                            <div class="rating">
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                            </div>
                            <h5>$67.24</h5>
                            <div class="product__color__select">
                                <label for="pc-4">
                                    <input type="radio" id="pc-4">
                                </label>
                                <label class="active black" for="pc-5">
                                    <input type="radio" id="pc-5">
                                </label>
                                <label class="grey" for="pc-6">
                                    <input type="radio" id="pc-6">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-sm-6">
                    <div class="product__item sale">
                        <div class="product__item__pic set-bg" data-setbg="img/product/product-3.jpg">
                            <span class="label">Sale</span>
                            <ul class="product__hover">
                                <li><a href="#"><img src="img/icon/heart.png" alt=""></a></li>
                                <li><a href="#"><img src="img/icon/compare.png" alt=""> <span>Compare</span></a></li>
                                <li><a href="#"><img src="img/icon/search.png" alt=""></a></li>
                            </ul>
                        </div>
                        <div class="product__item__text">
                            <h6>Multi-pocket Chest Bag</h6>
                            <a href="#" class="add-cart">+ Add To Cart</a>
                            <div class="rating">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star-o"></i>
                            </div>
                            <h5>$43.48</h5>
                            <div class="product__color__select">
                                <label for="pc-7">
                                    <input type="radio" id="pc-7">
                                </label>
                                <label class="active black" for="pc-8">
                                    <input type="radio" id="pc-8">
                                </label>
                                <label class="grey" for="pc-9">
                                    <input type="radio" id="pc-9">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-sm-6">
                    <div class="product__item">
                        <div class="product__item__pic set-bg" data-setbg="img/product/product-4.jpg">
                            <ul class="product__hover">
                                <li><a href="#"><img src="img/icon/heart.png" alt=""></a></li>
                                <li><a href="#"><img src="img/icon/compare.png" alt=""> <span>Compare</span></a></li>
                                <li><a href="#"><img src="img/icon/search.png" alt=""></a></li>
                            </ul>
                        </div>
                        <div class="product__item__text">
                            <h6>Diagonal Textured Cap</h6>
                            <a href="#" class="add-cart">+ Add To Cart</a>
                            <div class="rating">
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                            </div>
                            <h5>$60.9</h5>
                            <div class="product__color__select">
                                <label for="pc-10">
                                    <input type="radio" id="pc-10">
                                </label>
                                <label class="active black" for="pc-11">
                                    <input type="radio" id="pc-11">
                                </label>
                                <label class="grey" for="pc-12">
                                    <input type="radio" id="pc-12">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Related Section End -->

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
        function setActiveColor(selectedInput) {
            const colorLabels = document.querySelectorAll('.product__details__option__color label');

            colorLabels.forEach(label => label.classList.remove('active'));

            selectedInput.parentElement.classList.add('active');
        }

    </script>
</body>

</html>
<?php
$conn->close(); // Đóng kết nối
?>