<?php
// Kết nối đến cơ sở dữ liệu
include 'db_connect.php';

// Kiểm tra xem có variant_id được gửi đến không
if (!isset($_GET['variant_id']) || !is_numeric($_GET['variant_id'])) {
    echo json_encode(['error' => 'Invalid Variant ID']);
    exit;
}

$variant_id = intval($_GET['variant_id']);

// Truy vấn để lấy thông tin chi tiết về biến thể
$query = "
    SELECT 
        pv.variant_id,
        pv.product_id,
        p.product_name,
        p.price,
        p.sale_price,
        s.size_name,
        c.color_name,
        c.color_code,
        sku.sku_code,
        sku.stock
    FROM 
        product_variants pv
    JOIN 
        products p ON pv.product_id = p.product_id
    LEFT JOIN 
        sizes s ON pv.size_id = s.size_id
    LEFT JOIN 
        colors c ON pv.color_id = c.color_id
    LEFT JOIN 
        sku ON pv.variant_id = sku.variant_id
    WHERE 
        pv.variant_id = ?
";

$stmt = $conn->prepare($query);
if (!$stmt) {
    echo json_encode(['error' => 'Query preparation failed']);
    exit;
}

$stmt->bind_param("i", $variant_id);
if (!$stmt->execute()) {
    echo json_encode(['error' => 'Query execution failed']);
    exit;
}

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $variant_info = $result->fetch_assoc();
    
    // Định dạng kết quả
    $response = [
        'variant_id' => $variant_info['variant_id'],
        'product_id' => $variant_info['product_id'],
        'product_name' => $variant_info['product_name'],
        'price' => $variant_info['price'],
        'sale_price' => $variant_info['sale_price'] ?? null,
        'size_name' => $variant_info['size_name'] ?? null,
        'color_name' => $variant_info['color_name'] ?? null,
        'color_code' => $variant_info['color_code'] ?? null,
        'sku_code' => $variant_info['sku_code'] ?? null,
        'stock' => $variant_info['stock'] ?? 0
    ];
    
    echo json_encode($response);
} else {
    echo json_encode(['error' => 'Variant not found']);
}

$stmt->close();
$conn->close();
?>