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

// Truy vấn để lấy các biến thể của sản phẩm và thông tin SKU
$variantQuery = "SELECT pv.*, s.size_name, c.color_name, c.color_code, sku.stock, sku.price as variant_price
                 FROM product_variants pv
                 LEFT JOIN sizes s ON pv.size_id = s.size_id
                 LEFT JOIN colors c ON pv.color_id = c.color_id
                 LEFT JOIN sku ON pv.variant_id = sku.variant_id
                 WHERE pv.product_id = ?";
$stmt = $conn->prepare($variantQuery);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$variantResult = $stmt->get_result();
$variants = $variantResult->fetch_all(MYSQLI_ASSOC);

// Tổ chức lại dữ liệu biến thể
$productVariants = [];
foreach ($variants as $variant) {
    if (!isset($productVariants[$variant['color_id']])) {
        $productVariants[$variant['color_id']] = [
            'color_name' => $variant['color_name'],
            'color_code' => $variant['color_code'],
            'sizes' => []
        ];
    }
    $productVariants[$variant['color_id']]['sizes'][] = [
        'variant_id' => $variant['variant_id'],
        'size_id' => $variant['size_id'],
        'size_name' => $variant['size_name'],
        'stock' => $variant['stock'],
        'price' => $variant['variant_price']
    ];
}

// Thêm ghi log để gỡ lỗi
error_log("Product Variants: " . print_r($productVariants, true));

$productDataJson = htmlspecialchars(json_encode([
    'id' => $product['product_id'],
    'name' => $product['product_name'],
    'price' => $product['price'],
    'sale_price' => $product['sale_price'],
    'image' => $product['product_image'],
    'variants' => $productVariants
]), ENT_QUOTES, 'UTF-8');

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
                <h3>
                    <?php if ($product['sale_price'] && $product['sale_price'] < $product['price']): ?>
                        <?php echo number_format($product['sale_price'], 0, ',', '.'); ?>đ
                        <span style="text-decoration: line-through; color: #b2b2b2; font-size: 0.8em;">
                            <?php echo number_format($product['price'], 0, ',', '.'); ?>đ
                        </span>
                    <?php else: ?>
                        <?php echo number_format($product['price'], 0, ',', '.'); ?>đ
                    <?php endif; ?>
                </h3>
                <p><?php echo htmlspecialchars($product['description']); ?></p>
                <div class="product__details__option">
    <div class="row">
        <div class="col-12 mb-4">
            <div class="product__details__option__color">
                <h6 class="mb-3">Màu sắc:</h6>
                <div class="d-flex flex-wrap">
                    <?php foreach ($productVariants as $colorId => $colorData): ?>
                        <div class="form-check form-check-inline mr-3 mb-2">
                            <input class="form-check-input d-none" type="radio" id="color-<?php echo $colorId; ?>"
                                   name="color" value="<?php echo $colorId; ?>"
                                   onchange="updateSizeOptions(this.value, '<?php echo $productDataJson; ?>')">
                            <label class="form-check-label color-option" for="color-<?php echo $colorId; ?>"
                                   style="background-color: <?php echo $colorData['color_code']; ?>; width: 30px; height: 30px; border-radius: 50%; cursor: pointer; border: 2px solid #ddd;">
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-4">
            <div class="product__details__option__size">
                <h6 class="mb-3">Kích thước:</h6>
                <div id="size-options" class="d-flex flex-wrap">
                    <!-- Kích thước sẽ được cập nhật động bằng JavaScript -->
                </div>
            </div>
        </div>
    </div>
<div class="product__details__cart__option">
    <div class="quantity">
        <div class="pro-qty">
            <input type="text" value="1" id="quantity">
        </div>
    </div>
    <a href="javascript:void(0)" class="primary-btn" onclick="addToCartWithVariant('<?php echo $productDataJson; ?>')">
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
                                <!-- <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#tabs-5" role="tab">Mô Tả</a>
                                </li>
                                --> 
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
                    <h3 class="related-title">Sản phẩm liên quan</h3>
                </div>
            </div>
            <div class="row">
                <?php
                // Lấy category_id của sản phẩm hiện tại
                $current_category_id = $product['category_id'];
                
                // Truy vấn để lấy các sản phẩm liên quan
                $related_sql = "SELECT p.*, b.brand_name 
                                FROM products p
                                LEFT JOIN brands b ON p.brand_id = b.brand_id
                                WHERE p.category_id = ? AND p.product_id != ?
                                ORDER BY RAND()
                                LIMIT 4";
                $stmt = $conn->prepare($related_sql);
                $stmt->bind_param("ii", $current_category_id, $product_id);
                $stmt->execute();
                $related_result = $stmt->get_result();

                while ($related_product = $related_result->fetch_assoc()) {
                    ?>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="product__item">
                            <div class="product__item__pic set-bg" data-setbg="<?php echo htmlspecialchars($related_product['product_image']); ?>">
                                <?php if ($related_product['is_new_arrival']): ?>
                                    <span class="label">New</span>
                                <?php endif; ?>
                                <ul class="product__hover">
                                    <li><a href="#"><img src="assets/img/icon/heart.png" alt=""></a></li>
                                    <li><a href="#"><img src="assets/img/icon/compare.png" alt=""> <span>So sánh</span></a></li>
                                    <li><a href="shop-details.php?id=<?php echo $related_product['product_id']; ?>"><img src="assets/img/icon/search.png" alt=""></a></li>
                                </ul>
                            </div>
                            <div class="product__item__text">
                                <h6><?php echo htmlspecialchars($related_product['product_name']); ?></h6>
                                <a href="shop-details.php?id=<?php echo $related_product['product_id']; ?>" class="add-cart">+ Xem chi tiết</a>
                                <div class="rating">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star-o"></i>
                                </div>
                                <?php if ($related_product['sale_price']): ?>
                                    <h5><span class="original-price"><?php echo number_format($related_product['price']); ?> đ</span> <?php echo number_format($related_product['sale_price']); ?> đ</h5>
                                <?php else: ?>
                                    <h5><?php echo number_format($related_product['price']); ?> đ</h5>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php
                }
                $stmt->close();
                ?>
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
        // Hàm này được sử dụng để đặt màu sắc được chọn là active
        function setActiveColor(selectedInput) {
            // Lấy tất cả các label màu sắc
            const colorLabels = document.querySelectorAll('.product__details__option__color label');

            // Xóa class 'active' từ tất cả các label
            colorLabels.forEach(label => label.classList.remove('active'));

            // Thêm class 'active' vào label của input được chọn
            selectedInput.parentElement.classList.add('active');
        }

    </script>
    <script>
        // Hàm này cập nhật các tùy chọn kích thước dựa trên màu sắc được chọn
        function updateSizeOptions(colorId, productDataJson) {
            // Parse dữ liệu sản phẩm từ JSON
            const product = JSON.parse(productDataJson);
            // Lấy container chứa các tùy chọn kích thước
            const sizeOptionsContainer = document.getElementById('size-options');
            // Xóa tất cả các tùy chọn kích thước hiện tại
            sizeOptionsContainer.innerHTML = '';

            // Nếu có các kích thước cho màu sắc đã chọn
            if (product.variants[colorId]) {
                // Tạo một label mới cho mỗi kích thước
                product.variants[colorId].sizes.forEach(size => {
                    const label = document.createElement('label');
                    label.innerHTML = `
                        <input type="radio" name="size" value="${size.variant_id}"
                               data-size-id="${size.size_id}"
                               onclick="setActiveSize(this)">
                        ${size.size_name}
                    `;
                    sizeOptionsContainer.appendChild(label);
                });
            }
        }

        // Hàm này đặt kích thước được chọn là active
        function setActiveSize(input) {
            const labels = input.closest('.product__details__option__size').querySelectorAll('label');
            labels.forEach(label => label.classList.remove('active'));
            input.parentElement.classList.add('active');
        }

        // Hàm này đặt màu sắc được chọn là active
        function setActiveColor(input) {
            const labels = input.closest('.product__details__option__color').querySelectorAll('label');
            labels.forEach(label => label.classList.remove('active'));
            input.parentElement.classList.add('active');
        }

        // Hàm này thêm sản phẩm vào giỏ hàng với biến thể đã chọn
        function addToCartWithVariant(productDataJson) {
            const product = JSON.parse(productDataJson);
            const colorInput = document.querySelector('input[name="color"]:checked');
            const sizeInput = document.querySelector('input[name="size"]:checked');
            const quantity = parseInt(document.getElementById('quantity').value);

            // Kiểm tra xem đã chọn màu sắc và kích thước chưa
            if (!colorInput || !sizeInput) {
                alert('Vui lòng chọn màu sắc và kích thước');
                return;
            }

            const colorId = colorInput.value;
            const variantId = sizeInput.value;
            const sizeId = sizeInput.dataset.sizeId;
            const selectedColor = product.variants[colorId].color_name;
            const selectedSize = product.variants[colorId].sizes.find(size => size.variant_id == variantId).size_name;

            // Tạo đối tượng sản phẩm để thêm vào giỏ hàng
            const cartItem = {
                product_id: product.id,
                variant_id: variantId,
                color_id: colorId,
                size_id: sizeId,
                name: product.name,
                price: product.sale_price && parseFloat(product.sale_price) > 0 ? product.sale_price : product.price,
                quantity: quantity,
                color: selectedColor,
                size: selectedSize,
                image: product.image
            };

            addToCart(cartItem);
        }

        // Hàm này thêm sản phẩm vào giỏ hàng
        function addToCart(item) {
            // Lấy giỏ hàng từ localStorage hoặc tạo mới nếu chưa có
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            
            // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
            const existingItemIndex = cart.findIndex(cartItem => 
                cartItem.variant_id === item.variant_id
            );

            if (existingItemIndex > -1) {
                // Nếu sản phẩm đã có, tăng số lượng
                cart[existingItemIndex].quantity += item.quantity;
            } else {
                // Nếu sản phẩm chưa có, thêm mới vào giỏ hàng
                cart.push(item);
            }

            // Lưu giỏ hàng vào localStorage
            localStorage.setItem('cart', JSON.stringify(cart));
            alert('Đã thêm vào giỏ hàng');
            // Cập nhật hiển thị giỏ hàng
            updateCartDisplay();
            // Cập nhật hiển thị giỏ hàng toàn cục
           
        }
    </script>
</body>

</html>
<?php
$conn->close(); // Đóng kết nối
?>