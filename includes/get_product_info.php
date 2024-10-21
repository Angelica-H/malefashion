<?php
include 'db_connect.php';

if (!isset($_GET['product_id'])) {
    echo json_encode(['error' => 'Product ID is required']);
    exit;
}

$product_id = intval($_GET['product_id']);

$query = "
    SELECT 
        p.product_id,
        p.product_name,
        p.description,
        p.price AS base_price,
        p.sale_price AS base_sale_price,
        p.product_image,
        p.is_best_seller,
        p.is_new_arrival,
        p.is_hot_sale,
        b.brand_name,
        c.category_name,
        pv.variant_id,
        pv.color_id,
        col.color_name,
        col.color_code,
        pv.size_id,
        s.size_name,
        sku.sku_code,
        sku.stock,
        sku.price,
        COALESCE(sku.price, p.price) AS final_price
    FROM 
        products p
    LEFT JOIN 
        brands b ON p.brand_id = b.brand_id
    LEFT JOIN 
        categories c ON p.category_id = c.category_id
    LEFT JOIN 
        product_variants pv ON p.product_id = pv.product_id
    LEFT JOIN 
        colors col ON pv.color_id = col.color_id
    LEFT JOIN 
        sizes s ON pv.size_id = s.size_id
    LEFT JOIN 
        sku ON pv.variant_id = sku.variant_id
    WHERE 
        p.product_id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

$product_info = [
    'product_id' => $product_id,
    'product_name' => '',
    'description' => '',
    'base_price' => 0,
    'base_sale_price' => null,
    'product_image' => '',
    'is_best_seller' => false,
    'is_new_arrival' => false,
    'is_hot_sale' => false,
    'brand_name' => '',
    'category_name' => '',
    'variants' => []
];

while ($row = $result->fetch_assoc()) {
    // Thông tin cơ bản về sản phẩm
    $product_info['product_name'] = $row['product_name'];
    $product_info['description'] = $row['description'];
    $product_info['base_price'] = $row['base_price'];
    $product_info['base_sale_price'] = $row['base_sale_price'];
    $product_info['product_image'] = $row['product_image'];
    $product_info['is_best_seller'] = (bool)$row['is_best_seller'];
    $product_info['is_new_arrival'] = (bool)$row['is_new_arrival'];
    $product_info['is_hot_sale'] = (bool)$row['is_hot_sale'];
    $product_info['brand_name'] = $row['brand_name'];
    $product_info['category_name'] = $row['category_name'];
    
    // Thông tin về biến thể
    if ($row['variant_id']) {
        if (!isset($product_info['variants'][$row['color_id']])) {
            $product_info['variants'][$row['color_id']] = [
                'color_name' => $row['color_name'],
                'color_code' => $row['color_code'],
                'sizes' => []
            ];
        }
        
        $product_info['variants'][$row['color_id']]['sizes'][] = [
            'size_id' => $row['size_id'],
            'size_name' => $row['size_name'],
            'variant_id' => $row['variant_id'],
            'sku_code' => $row['sku_code'],
            'price' => $row['final_price'],
            'stock' => $row['stock']
        ];
    }
}

echo json_encode($product_info);

$stmt->close();
$conn->close();