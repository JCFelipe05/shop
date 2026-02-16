<?php
$root_dir = $_SERVER['DOCUMENT_ROOT'];
include($root_dir . '/student008/shop/backend/config/connection.php');

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// ID del supplier del supplier
$supplier_id = 2;

// Obtener pedidos de productos del supplier
$sql = "SELECT 
            p.supplier_product_id,
            c.email,
            COUNT(*) as cantidad_total
        FROM 008_pedido pe
        JOIN 008_producto p ON pe.id_producto = p.id_producto
        JOIN 008_cliente c ON pe.id_cliente = c.id_cliente
        WHERE p.supplier_id = $supplier_id
        GROUP BY p.supplier_product_id, c.email
        ORDER BY pe.fecha_pedido DESC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en la consulta'
    ]);
    exit;
}

$pedidos = [];
while ($row = mysqli_fetch_assoc($result)) {
    $pedidos[] = [
        'supplier_product_id' => $row['supplier_product_id'],
        'email' => $row['email'],
        'address' => 'Test address',
        'cantidad' => (int)$row['cantidad_total']
    ];
}

mysqli_free_result($result);
mysqli_close($conn);

$datos = json_encode($pedidos);

$ch = curl_init('http://sitio.com/recibir.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $datos); // Enviar JSON
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($datos)
));

$result = curl_exec($ch);
curl_close($ch);
?>