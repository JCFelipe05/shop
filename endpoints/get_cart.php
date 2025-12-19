<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$root_dir = $_SERVER['DOCUMENT_ROOT'];
include($root_dir . '/student008/shop/backend/config/connection.php');

// Verificar sesión
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'error' => 'Usuario no autenticado',
        'redirect' => '/student008/shop/backend/forms/form_login.php'
    ]);
    exit();
}

if (!$conn) {
    echo json_encode([
        'success' => false,
        'error' => 'Error de conexión a base de datos'
    ]);
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT 
            c.id_producto,
            c.cantidad,
            p.nombre_producto,
            p.descripcion,
            p.precio
        FROM 008_carrito c
        JOIN 008_producto p ON c.id_producto = p.id_producto
        WHERE c.id_cliente = $user_id
        ORDER BY c.id_producto DESC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode([
        'success' => false,
        'error' => mysqli_error($conn)
    ]);
    exit();
}

$items = [];
$subtotal = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $itemTotal = (float)$row['precio'] * (int)$row['cantidad'];
    $subtotal += $itemTotal;
    
    $items[] = [
        'id' => (int)$row['id_producto'],
        'name' => $row['nombre_producto'],
        'description' => $row['descripcion'] ?? '',
        'price' => (float)$row['precio'],
        'quantity' => (int)$row['cantidad'],
        'total' => $itemTotal,
        'image' => '/student008/shop/assets/img/pulsera.jpg'
    ];
}

mysqli_free_result($result);
mysqli_close($conn);

$shipping = 3.99;
$total = $subtotal + $shipping;

echo json_encode([
    'success' => true,
    'items' => $items,
    'summary' => [
        'subtotal' => $subtotal,
        'shipping' => $shipping,
        'total' => $total,
        'itemCount' => count($items)
    ]
]);
?>