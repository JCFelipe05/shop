<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$root_dir = $_SERVER['DOCUMENT_ROOT'];
include($root_dir . '/student008/shop/backend/config/connection.php');

// Obtener API key del parámetro GET
$api_key = $_GET['api_key'] ?? '';

// Validar que se proporcionó una API key
if (empty($api_key)) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'API key requerida.'
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    mysqli_close($conn);
    exit;
}

// Buscar el seller por API key
$api_key_escaped = mysqli_real_escape_string($conn, $api_key);
$sql_seller = "SELECT supplier_id, name 
               FROM 008_sellers 
               WHERE api_key = '$api_key_escaped'";

$result_seller = mysqli_query($conn, $sql_seller);

if (!$result_seller || mysqli_num_rows($result_seller) === 0) {
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'message' => 'API key inválida'
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    mysqli_close($conn);
    exit;
}

$seller = mysqli_fetch_assoc($result_seller);
$supplier_id = $seller['supplier_id'];

// Obtener los productos que este seller puede acceder
$sql_products = "SELECT 
                    p.id_producto as id,
                    p.nombre_producto as name,
                    p.precio as price
                FROM 008_producto p
                INNER JOIN 008_product_sellers ps ON p.id_producto = ps.product_id
                WHERE ps.seller_id = $supplier_id
                ORDER BY p.id_producto DESC";

$result_products = mysqli_query($conn, $sql_products);

if (!$result_products) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error en la consulta: ' . mysqli_error($conn)
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    mysqli_close($conn);
    exit;
}

// Construir array de productos
$products = [];
while ($row = mysqli_fetch_assoc($result_products)) {
    $products[] = [
        'id' => (int)$row['id'],
        'name' => $row['name'],
        'price' => (float)$row['price']
    ];
}

mysqli_free_result($result_products);
mysqli_close($conn);

// Devolver productos en formato JSON
http_response_code(200);
echo json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>