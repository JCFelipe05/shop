<?php

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$root_dir = $_SERVER['DOCUMENT_ROOT'];
include($root_dir . '/student008/shop/backend/config/connection.php');

// Parámetros opcionales
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : null;
$category = isset($_GET['category']) ? intval($_GET['category']) : null;
$featured = isset($_GET['featured']) ? true : false;

// Construir la consulta
$sql = "SELECT 
            id_producto,
            nombre_producto,
            descripcion,
            color,
            medida,
            precio
        FROM 008_producto 
        WHERE 1=1";

// Filtrar por categoría si se proporciona
if ($category !== null) {
    $sql .= " AND id_categoria = " . $category;
}

// Ordenar
$sql .= " ORDER BY id_producto DESC";

// Limitar resultados si se proporciona
if ($limit !== null && $limit > 0) {
    $sql .= " LIMIT " . $limit;
}

try {
    $result = mysqli_query($conn, $sql);
    
    if (!$result) {
        throw new Exception(mysqli_error($conn));
    }
    
    $products = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = [
            'id' => (int)$row['id_producto'],
            'name' => $row['nombre_producto'],
            'description' => $row['descripcion'],
            'color' => $row['color'],
            'size' => $row['medida'],
            'price' => (float)$row['precio'],
            // Ruta de imagen por defecto (puedes personalizarla)
            'image' => '/student008/shop/assets/img/pulsera.jpg'
        ];
    }
    
    mysqli_free_result($result);
    mysqli_close($conn);
    
    // Respuesta exitosa
    echo json_encode([
        'success' => true,
        'count' => count($products),
        'products' => $products
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    // Respuesta de error
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Error al obtener productos',
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>