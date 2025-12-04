<?php
session_start();
header('Content-Type: application/json');
$root_dir = $_SERVER['DOCUMENT_ROOT'];
include($root_dir . '/student008/shop/backend/config/connection.php');

if (!isset($_POST['id_producto'])) {
    echo json_encode(['success' => false, 'message' => 'ID faltante']);
    exit;
}

$id_producto = intval($_POST['id_producto']);

// Se lee la cantidad actual del producto en el carrito
$sql = "SELECT cantidad FROM 008_carrito WHERE id_producto = $id_producto AND id_cliente = " . $_SESSION['user_id'] . " LIMIT 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    echo json_encode(['success' => false, 'message' => 'Producto no encontrado']);
    exit;
}

$cantidad_actual = intval($row['cantidad']);

if ($cantidad_actual > 1) {
    // Restamos la cantidad en caso de que haya más de 1 producto del mismo tipo
    $sql_update = "UPDATE 008_carrito SET cantidad = cantidad - 1 WHERE id_producto = $id_producto AND id_cliente = " . $_SESSION['user_id'];
    mysqli_query($conn, $sql_update);

    echo json_encode([
        'success' => true,
        'remaining' => $cantidad_actual - 1
    ]);
} else {
    // Eliminamos si solo queda 1 producto del mismo tipo
    $sql_delete = "DELETE FROM 008_carrito WHERE id_producto = $id_producto AND id_cliente = " . $_SESSION['user_id'];
    mysqli_query($conn, $sql_delete);

    echo json_encode([
        'success' => true,
        'remaining' => 0
    ]);
}

mysqli_close($conn);
?>