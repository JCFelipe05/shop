<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$root_dir = $_SERVER['DOCUMENT_ROOT'];
include($root_dir . '/student008/shop/backend/config/connection.php');

if (!$conn) {
    echo json_encode([
        'success' => false,
        'error' => 'Error de conexión a base de datos'
    ]);
    exit();
}

// Obtener ID del producto
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    echo json_encode([
        'success' => false,
        'error' => 'ID de producto no válido'
    ]);
    exit();
}

$sql = "SELECT 
            id_producto,
            nombre_producto,
            descripcion,
            color,
            medida,
            precio
        FROM 008_producto 
        WHERE id_producto = $id";

$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode([
        'success' => false,
        'error' => mysqli_error($conn)
    ]);
    exit();
}

$product = mysqli_fetch_assoc($result);

if (!$product) {
    echo json_encode([
        'success' => false,
        'error' => 'Producto no encontrado'
    ]);
    exit();
}

mysqli_free_result($result);
mysqli_close($conn);

echo json_encode([
    'success' => true,
    'product' => [
        'id' => (int) $product['id_producto'],
        'name' => $product['nombre_producto'],
        'description' => $product['descripcion'] ?? '',
        'color' => $product['color'] ?? '',
        'size' => $product['medida'] ?? '',
        'price' => (float) $product['precio'],
        'image' => '/student008/shop/assets/img/pulsera.jpg'
    ]
]);
?>