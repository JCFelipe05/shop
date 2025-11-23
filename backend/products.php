<?php
// Configuración de conexión
$root_dir = $_SERVER['DOCUMENT_ROOT'];
include($root_dir . '/student008/shop/backend/config/connection.php');

$sql = "SELECT nombre_producto, descripcion, precio FROM 008_producto";

$result = mysqli_query($conn, $sql);

$products = [];

if($result) {
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC); 
    mysqli_free_result($result);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

    <h1>Productos hechos con Paracord</h1>
    <br>
    <div class="contenedor-productos">
        <?php foreach ($products as $product): ?>
            <div class="producto">
                <h3><?= htmlspecialchars($product['nombre_producto']) ?></h3>
                <p class="detalle"><?= htmlspecialchars($product['descripcion']) ?></p>
                <p class="precio"><?= number_format($product['precio'], 2) ?> €</p>
            </div>
            <br>
        <?php endforeach; ?>
    </div>

</body>
</html>