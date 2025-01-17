<?php
$current_page = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
?>

<div class="app-sidebar__inner">
    <ul class="vertical-nav-menu">
        <li class="app-sidebar__heading">Menu</li>
        <li class="mm-active">
            <a href="#">
                <i class="metismenu-icon pe-7s-plugin"></i>Applications
                <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
            </a>
            <ul>
                <li>
                    <a href="./index.php" class="<?php echo ($current_page == 'index.php') ? 'mm-active' : ''; ?>">
                        <i class="metismenu-icon"></i>User
                    </a>
                </li>
                <li>
                    <a href="./order.php" class="<?php echo ($current_page == 'order.php') ? 'mm-active' : ''; ?>">
                        <i class="metismenu-icon"></i>Order
                    </a>
                </li>
                <li>
                    <a href="./product.php" class="<?php echo ($current_page == 'product.php') ? 'mm-active' : ''; ?>">
                        <i class="metismenu-icon"></i>Product
                    </a>
                </li>
                <li>
                    <a href="./product-sku.php" class="<?php echo ($current_page == 'product-sku.php') ? 'mm-active' : ''; ?>">
                        <i class="metismenu-icon"></i>Product SKU
                    </a>
                </li>
                <li>
                    <a href="./category.php" class="<?php echo ($current_page == 'category.php') ? 'mm-active' : ''; ?>">
                        <i class="metismenu-icon"></i>Category
                    </a>
                </li>
                <li>
                    <a href="./brand.php" class="<?php echo ($current_page == 'brand.php') ? 'mm-active' : ''; ?>">
                        <i class="metismenu-icon"></i>Brand
                    </a>
                </li>
                <li>
                    <a href="./manage_reviews.php" class="<?php echo ($current_page == 'manage_reviews.php') ? 'mm-active' : ''; ?>">
                        <i class="metismenu-icon"></i>Reviews
                    </a>
                </li>
                <li>
                    <a href="./report.php" class="<?php echo ($current_page == 'report.php') ? 'mm-active' : ''; ?>">
                        <i class="metismenu-icon"></i>Report
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</div>
