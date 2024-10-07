<?php
include 'includes/db_connect.php'; // Kết nối cơ sở dữ liệu

session_start();

// Lấy tham số tìm kiếm, phân trang, sắp xếp
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) && $_GET['category'] !== 'all' ? $_GET['category'] : null;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'price-asc';
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$limit = 12;
$offset = ($page - 1) * $limit;

// Lấy thông tin kích thước và màu sắc từ form
$selectedSize = isset($_GET['size']) ? $_GET['size'] : null;
$selectedColor = isset($_GET['color']) ? $_GET['color'] : null;

// Câu truy vấn SQL động
$query = "SELECT * FROM products WHERE 1=1";

// Thêm điều kiện tìm kiếm
if ($search) {
    $query .= " AND (product_name LIKE '%$search%' OR description LIKE '%$search%')";
}

// Thêm điều kiện lọc danh mục
if ($category) {
    $query .= " AND category_id = '$category'";
}

// Thêm điều kiện lọc kích thước
if ($selectedSize) {
    $query .= " AND product_id IN (SELECT product_id FROM product_variants WHERE size_id IN (SELECT size_id FROM sizes WHERE size_name = '$selectedSize'))";
}

// Thêm điều kiện lọc màu sắc
if ($selectedColor) {
    $query .= " AND product_id IN (SELECT product_id FROM product_variants WHERE color_id IN (SELECT color_id FROM colors WHERE color_name = '$selectedColor'))";
}

// Thêm điều kiện sắp xếp
if ($sort == 'price-asc') {
    $query .= " ORDER BY price ASC";
} elseif ($sort == 'price-desc') {
    $query .= " ORDER BY price DESC";
} elseif ($sort == 'name-asc') {
    $query .= " ORDER BY product_name ASC";
} elseif ($sort == 'name-desc') {
    $query .= " ORDER BY product_name DESC";
}

// Thêm phân trang
$query .= " LIMIT $offset, $limit";

// Thực thi truy vấn
$result = $conn->query($query);

// Đếm tổng số sản phẩm để phân trang
$totalQuery = "SELECT COUNT(*) as total FROM products WHERE 1=1";

// Thêm điều kiện tương tự vào truy vấn đếm
if ($search) {
    $totalQuery .= " AND (product_name LIKE '%$search%' OR description LIKE '%$search%')";
}

if ($category) {
    $totalQuery .= " AND category_id = '$category'";
}

if ($selectedSize) {
    $totalQuery .= " AND product_id IN (SELECT product_id FROM product_variants WHERE size_id IN (SELECT size_id FROM sizes WHERE size_name = '$selectedSize'))";
}

if ($selectedColor) {
    $totalQuery .= " AND product_id IN (SELECT product_id FROM product_variants WHERE color_id IN (SELECT color_id FROM colors WHERE color_name = '$selectedColor'))";
}

$totalResult = $conn->query($totalQuery);
$totalProducts = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalProducts / $limit);
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

    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Shop</h4>
                        <div class="breadcrumb__links">
                            <a href="./index.html">Home</a>
                            <span>Shop</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Shop Section Begin -->
    <section class="shop spad">
        <form method="GET" action="">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="shop__sidebar">
                            <div class="shop__sidebar__search">
                                <form action="#">
                                    <input type="text" placeholder="Search...">
                                    <button type="submit"><span class="icon_search"></span></button>
                                </form>
                            </div>
                            <div class="shop__sidebar__accordion">
                                <div class="accordion" id="accordionExample">
                                    <div class="card">
                                        <div class="card-heading">
                                            <a data-toggle="collapse" data-target="#collapseOne">Categories</a>
                                        </div>
                                        <div id="collapseOne" class="collapse show" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="shop__sidebar__categories">
                                                    <ul class="nice-scroll">
                                                        <li><a href="#">Men (20)</a></li>
                                                        <li><a href="#">Women (20)</a></li>
                                                        <li><a href="#">Bags (20)</a></li>
                                                        <li><a href="#">Clothing (20)</a></li>
                                                        <li><a href="#">Shoes (20)</a></li>
                                                        <li><a href="#">Accessories (20)</a></li>
                                                        <li><a href="#">Kids (20)</a></li>
                                                        <li><a href="#">Kids (20)</a></li>
                                                        <li><a href="#">Kids (20)</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="card">
                                    <div class="card-heading">
                                        <a data-toggle="collapse" data-target="#collapseTwo">Branding</a>
                                    </div>
                                    <div id="collapseTwo" class="collapse show" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div class="shop__sidebar__brand">
                                                <ul>
                                                    <li><a href="#">Louis Vuitton</a></li>
                                                    <li><a href="#">Chanel</a></li>
                                                    <li><a href="#">Hermes</a></li>
                                                    <li><a href="#">Gucci</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                                    <!-- <div class="card">
                                    <div class="card-heading">
                                        <a data-toggle="collapse" data-target="#collapseThree">Filter Price</a>
                                    </div>
                                    <div id="collapseThree" class="collapse show" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div class="shop__sidebar__price">
                                                <ul>
                                                    <li><a href="#">$0.00 - $50.00</a></li>
                                                    <li><a href="#">$50.00 - $100.00</a></li>
                                                    <li><a href="#">$100.00 - $150.00</a></li>
                                                    <li><a href="#">$150.00 - $200.00</a></li>
                                                    <li><a href="#">$200.00 - $250.00</a></li>
                                                    <li><a href="#">250.00+</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                                    <?php
                                    include './includes/db_connect.php';

                                    // Truy vấn để lấy danh sách kích thước và màu sắc
                                    $sizeQuery = "SELECT * FROM sizes";
                                    $colorQuery = "SELECT * FROM colors";

                                    $sizeResult = $conn->query($sizeQuery);
                                    $colorResult = $conn->query($colorQuery);

                                    // Lưu kích thước vào mảng
                                    $sizes = $sizeResult->num_rows > 0 ? $sizeResult->fetch_all(MYSQLI_ASSOC) : [];

                                    // Lưu màu sắc vào mảng
                                    $colors = $colorResult->num_rows > 0 ? $colorResult->fetch_all(MYSQLI_ASSOC) : [];
                                    ?>
                                    <div class="card">
                                        <div class="card-heading">
                                            <a data-toggle="collapse" data-target="#collapseFour">Size</a>
                                        </div>
                                        <div id="collapseFour" class="collapse show" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="shop__sidebar__size">
                                                    <?php foreach ($sizes as $size): ?>
                                                        <label
                                                            class="<?php echo ($selectedSize == $size['size_name']) ? 'active' : ''; ?>"
                                                            for="<?php echo htmlspecialchars($size['size_name']); ?>">
                                                            <input type="radio"
                                                                id="<?php echo htmlspecialchars($size['size_name']); ?>"
                                                                name="size"
                                                                value="<?php echo htmlspecialchars($size['size_name']); ?>"
                                                                onchange="this.form.submit()">
                                                            <?php echo htmlspecialchars($size['size_name']); ?>
                                                        </label>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-heading">
                                            <a data-toggle="collapse" data-target="#collapseFive">Colors</a>
                                        </div>
                                        <div id="collapseFive" class="collapse show" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="shop__sidebar__color">
                                                    <?php foreach ($colors as $color): ?>
                                                        <label
                                                            class="<?php echo ($selectedColor == $color['color_code']) ? 'active' : ''; ?>"
                                                            class="c-<?php echo $color['color_id']; ?>"
                                                            for="color-<?php echo $color['color_id']; ?>"
                                                            style="background-color: <?php echo htmlspecialchars($color['color_code']); ?>">
                                                            <input type="radio" id="color-<?php echo $color['color_id']; ?>"
                                                                name="color"
                                                                value="<?php echo htmlspecialchars($color['color_code']); ?>"
                                                                onchange="this.form.submit()">
                                                        </label>
                                                    <?php endforeach; ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="card">
                                    <div class="card-heading">
                                        <a data-toggle="collapse" data-target="#collapseSix">Tags</a>
                                    </div>
                                    <div id="collapseSix" class="collapse show" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div class="shop__sidebar__tags">
                                                <a href="#">Product</a>
                                                <a href="#">Bags</a>
                                                <a href="#">Shoes</a>
                                                <a href="#">Fashio</a>
                                                <a href="#">Clothing</a>
                                                <a href="#">Hats</a>
                                                <a href="#">Accessories</a>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="shop__product__option">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="shop__product__option__left">
                                        <p>Showing 1–12 of 126 results</p>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="shop__product__option__right">
                                        <p>Sort by Price:</p>
                                        <select name="sort" onchange="this.form.submit()">
                                            <option value="price-asc" <?php echo ($sort == 'price-asc') ? 'selected' : ''; ?>>Low To High</option>
                                            <option value="price-desc" <?php echo ($sort == 'price-desc') ? 'selected' : ''; ?>>High To Low</option>
                                            <option value="name-asc" <?php echo ($sort == 'name-asc') ? 'selected' : ''; ?>>Name A-Z</option>
                                            <option value="name-desc" <?php echo ($sort == 'name-desc') ? 'selected' : ''; ?>>Name Z-A</option>
                                        </select>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <?php if ($result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <div class="col-lg-4 col-md-6 col-sm-6">
                                        <div class="product__item">
                                            <div class="product__item__pic set-bg"
                                                data-setbg="img/product/<?php echo $row['product_image']; ?>">
                                                <ul class="product__hover">
                                                    <li><a href="#"><img src="img/icon/heart.png" alt=""></a></li>
                                                    <li><a href="#"><img src="img/icon/compare.png" alt="">
                                                            <span>Compare</span></a>
                                                    </li>
                                                    <li><a href="shop-details.php?id=<?php echo $row['product_id']; ?>"><img
                                                                src="img/icon/search.png" alt=""></a></li>
                                                </ul>
                                            </div>
                                            <div class="product__item__text">
                                                <h6><?php echo $row['product_name']; ?></h6>
                                                <a href="javascript:void(0)" class="add-cart"
                                    data-product-id="<?php echo $row['product_id']; ?>"
                                    data-product-name="<?php echo htmlspecialchars($row['product_name']); ?>"
                                    data-product-price="<?php echo $row['price']; ?>" onclick="addToCart(
                                        '<?php echo $row['product_id']; ?>',
                                        '<?php echo addslashes($row['product_name']); ?>',
                                        <?php echo $row['price']; ?>, 
                                        1,  
                                        null,
                                        (document.querySelector('input[name=color_<?php echo $row['product_id']; ?>]:checked')?.value ?? 'Black')
                                        )">
                                    Thêm vào giỏ hàng
                                </a>
                                                <div class="rating">
                                                    <i class="fa fa-star-o"></i>
                                                    <i class="fa fa-star-o"></i>
                                                    <i class="fa fa-star-o"></i>
                                                    <i class="fa fa-star-o"></i>
                                                    <i class="fa fa-star-o"></i>
                                                </div>
                                                <h5>$<?php echo number_format($row['price'], 2); ?></h5>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <p>Không có sản phẩm nào.</p>
                            <?php endif; ?>
                            <!---

                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <div class="product__item">
                                <div class="product__item__pic set-bg" data-setbg="img/product/product-14.jpg">
                                    <ul class="product__hover">
                                        <li><a href="#"><img src="img/icon/heart.png" alt=""></a></li>
                                        <li><a href="#"><img src="img/icon/compare.png" alt=""> <span>Compare</span></a>
                                        </li>
                                        <li><a href="#"><img src="img/icon/search.png" alt=""></a></li>
                                    </ul>
                                </div>
                                <div class="product__item__text">
                                    <h6>Basic Flowing Scarf</h6>
                                    <a href="#" class="add-cart">+ Add To Cart</a>
                                    <div class="rating">
                                        <i class="fa fa-star-o"></i>
                                        <i class="fa fa-star-o"></i>
                                        <i class="fa fa-star-o"></i>
                                        <i class="fa fa-star-o"></i>
                                        <i class="fa fa-star-o"></i>
                                    </div>
                                    <h5>$26.28</h5>
                                    <div class="product__color__select">
                                        <label for="pc-40">
                                            <input type="radio" id="pc-40">
                                        </label>
                                        <label class="active black" for="pc-41">
                                            <input type="radio" id="pc-41">
                                        </label>
                                        <label class="grey" for="pc-42">
                                            <input type="radio" id="pc-42">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        --->
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="product__pagination">
                                    <a class="active" href="#">1</a>
                                    <a href="#">2</a>
                                    <a href="#">3</a>
                                    <span>...</span>
                                    <a href="#">21</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
    <!-- Shop Section End -->

    <!-- Footer Section Begin -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer__about">
                        <div class="footer__logo">
                            <a href="#"><img src="img/footer-logo.png" alt=""></a>
                        </div>
                        <p>The customer is at the heart of our unique business model, which includes design.</p>
                        <a href="#"><img src="img/payment.png" alt=""></a>
                    </div>
                </div>
                <div class="col-lg-2 offset-lg-1 col-md-3 col-sm-6">
                    <div class="footer__widget">
                        <h6>Shopping</h6>
                        <ul>
                            <li><a href="#">Clothing Store</a></li>
                            <li><a href="#">Trending Shoes</a></li>
                            <li><a href="#">Accessories</a></li>
                            <li><a href="#">Sale</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-6">
                    <div class="footer__widget">
                        <h6>Shopping</h6>
                        <ul>
                            <li><a href="#">Contact Us</a></li>
                            <li><a href="#">Payment Methods</a></li>
                            <li><a href="#">Delivary</a></li>
                            <li><a href="#">Return & Exchanges</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 offset-lg-1 col-md-6 col-sm-6">
                    <div class="footer__widget">
                        <h6>NewLetter</h6>
                        <div class="footer__newslatter">
                            <p>Be the first to know about new arrivals, look books, sales & promos!</p>
                            <form action="#">
                                <input type="text" placeholder="Your email">
                                <button type="submit"><span class="icon_mail_alt"></span></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="footer__copyright__text">
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                        <p>Copyright ©
                            <script>
                                document.write(new Date().getFullYear());
                            </script>2020
                            All rights reserved | This template is made with <i class="fa fa-heart-o"
                                aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
                        </p>
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                    </div>
                </div>
            </div>
        </div>
    </footer>
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

</html>