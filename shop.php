<?php
include 'includes/db_connect.php'; // Kết nối cơ sở dữ liệu

session_start();

// Xử lý các tham số tìm kiếm và lọc
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$brand_id = isset($_GET['brand']) ? (int)$_GET['brand'] : 0;
$size_id = isset($_GET['size']) ? (int)$_GET['size'] : 0;
$color_id = isset($_GET['color']) ? (int)$_GET['color'] : 0;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'price-asc';

// Xây dựng câu truy vấn SQL
$sql = "SELECT DISTINCT p.*, c.category_name, b.brand_name 
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.category_id
        LEFT JOIN brands b ON p.brand_id = b.brand_id
        LEFT JOIN product_variants pv ON p.product_id = pv.product_id
        WHERE 1=1";

if (!empty($search)) {
    $sql .= " AND (p.product_name LIKE '%$search%' OR c.category_name LIKE '%$search%' OR b.brand_name LIKE '%$search%')";
}
if ($category_id > 0) {
    $sql .= " AND p.category_id = $category_id";
}
if ($brand_id > 0) {
    $sql .= " AND p.brand_id = $brand_id";
}
if ($size_id > 0) {
    $sql .= " AND pv.size_id = $size_id";
}
if ($color_id > 0) {
    $sql .= " AND pv.color_id = $color_id";
}

// Sắp xếp
switch ($sort) {
    case 'price-asc':
        $sql .= " ORDER BY p.price ASC";
        break;
    case 'price-desc':
        $sql .= " ORDER BY p.price DESC";
        break;
    case 'name-asc':
        $sql .= " ORDER BY p.product_name ASC";
        break;
    case 'name-desc':
        $sql .= " ORDER BY p.product_name DESC";
        break;
    default:
        $sql .= " ORDER BY p.price ASC";
}

$result = $conn->query($sql);
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
                            <input type="text" name="search" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>">
                            <button type="submit"><span class="icon_search"></span></button>
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
                                                    <?php
                                                    $cat_sql = "SELECT c.*, COUNT(p.product_id) as product_count 
                                                                FROM categories c
                                                                LEFT JOIN products p ON c.category_id = p.category_id
                                                                GROUP BY c.category_id";
                                                    $cat_result = $conn->query($cat_sql);
                                                    while ($cat = $cat_result->fetch_assoc()) {
                                                        echo "<li><a href='?category={$cat['category_id']}'>{$cat['category_name']} ({$cat['product_count']})</a></li>";
                                                    }
                                                    ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-heading">
                                        <a data-toggle="collapse" data-target="#collapseTwo">Brands</a>
                                    </div>
                                    <div id="collapseTwo" class="collapse show" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div class="shop__sidebar__brand">
                                                <ul>
                                                    <?php
                                                    $brand_sql = "SELECT b.*, COUNT(p.product_id) as product_count 
                                                                  FROM brands b
                                                                  LEFT JOIN products p ON b.brand_id = p.brand_id
                                                                  GROUP BY b.brand_id";
                                                    $brand_result = $conn->query($brand_sql);
                                                    while ($brand = $brand_result->fetch_assoc()) {
                                                        echo "<li><a href='?brand={$brand['brand_id']}'>{$brand['brand_name']} ({$brand['product_count']})</a></li>";
                                                    }
                                                    ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="shop__product__option">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="shop__product__option__left">
                                    <p>Showing <?php echo $result->num_rows; ?> results</p>
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
                            <?php while ($row = $result->fetch_assoc()): 
                                $availableSizes = [];
                                $availableColors = [];
                                
                                // Fetch available sizes
                                $size_sql = "SELECT DISTINCT s.size_id, s.size_name 
                                             FROM product_variants pv 
                                             JOIN sizes s ON pv.size_id = s.size_id 
                                             WHERE pv.product_id = {$row['product_id']}";
                                $size_result = $conn->query($size_sql);
                                while ($size = $size_result->fetch_assoc()) {
                                    $availableSizes[] = $size['size_name'];
                                }

                                // Fetch available colors
                                $color_sql = "SELECT DISTINCT c.color_id, c.color_name, c.color_code 
                                              FROM product_variants pv 
                                              JOIN colors c ON pv.color_id = c.color_id 
                                              WHERE pv.product_id = {$row['product_id']}";
                                $color_result = $conn->query($color_sql);
                                while ($color = $color_result->fetch_assoc()) {
                                    $availableColors[] = $color['color_name'];
                                }

                                 // $productData = [
                                 //   'id' => $row['product_id'],
                                 //   'name' => $row['product_name'],
                                 //   'price' => $row['price'],
                                 //   'sale_price' => $row['sale_price'],
                                 //   'availableSizes' => $availableSizes,
                                 //   'availableColors' => $availableColors,
                                 //   'image' => $row['product_image']
                                 // ];
                                 // $productDataJson = htmlspecialchars(json_encode($productData), ENT_QUOTES, 'UTF-8');
                            ?>
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="product__item">
                                        <div class="product__item__pic set-bg" data-setbg="<?php echo htmlspecialchars($row['product_image']); ?>">
                                            <ul class="product__hover">
                                                <li><a href="#"><img src="assets/img/icon/heart.png" alt=""></a></li>
                                                <li><a href="#"><img src="assets/img/icon/compare.png" alt=""> <span>Compare</span></a></li>
                                                <li><a href="shop-details.php?id=<?php echo htmlspecialchars($row['product_id']); ?>"><img src="assets/img/icon/search.png" alt=""></a></li>
                                            </ul>
                                        </div>
                                        <div class="product__item__text">
                                            <h6><?php echo htmlspecialchars($row['product_name']); ?></h6>
                                            <a href="shop-details.php?id=<?php echo htmlspecialchars($row['product_id']); ?>" class="add-cart">
                        Xem chi tiết
                    </a>
                                            <div class="rating">
                                                <i class="fa fa-star-o"></i>
                                                <i class="fa fa-star-o"></i>
                                                <i class="fa fa-star-o"></i>
                                                <i class="fa fa-star-o"></i>
                                                <i class="fa fa-star-o"></i>
                                            </div>
                                            <?php if ($row['sale_price']): ?>
                                                <h5 style="text-decoration: line-through;"><?php echo number_format($row['price']); ?>đ</h5>
                                                <h5 class="sale-price" style="color: red;"><?php echo number_format($row['sale_price']); ?>đ</h5>
                                            <?php else: ?>
                                                <h5><?php echo number_format($row['price']); ?>đ</h5>
                                            <?php endif; ?>
                                            
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p>Không có sản phẩm nào.</p>
                        <?php endif; ?>
                    </div>
                    <!-- Phân trang có thể được thêm vào đây -->
                </div>
            </div>
        </div>
    </form>
</section>
    <!-- Shop Section End -->

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

</html>