<?php
// Configuración de conexión
$root_dir = $_SERVER['DOCUMENT_ROOT'];
include($root_dir . '/student008/shop/backend/config/connection.php');
include($root_dir . '/student008/shop/backend/header.php');

$sql = "SELECT id_producto, nombre_producto, descripcion, precio FROM 008_producto";

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
    <button><a href="/student008/shop/backend/forms/form_product_insert.php">Añadir producto</a></button>
    <div class="contenedor-productos">
        <?php foreach ($products as $product): ?>
            <div class="producto">
                <h3><?= htmlspecialchars($product['nombre_producto']) ?></h3>
                <p class="detalle"><?= htmlspecialchars($product['descripcion']) ?></p>
                <p class="precio"><?= number_format($product['precio'], 2) ?> €</p>
            </div>
            <button class="btn btn-secondary btn-small"><a href="/student008/shop/backend/forms/form_product_update_call.php">Update</a></button>
            <button>
                <a href="/student008/shop/backend/db/db_product_delete.php?id=<?= $product['id_producto'] ?>">
                    Eliminar
                </a>
            </button>
            <button>
                <a href="/student008/shop/backend/cart_insert.php?id=<?= $product['id_producto']?>">
                    Añadir al carrito
                </a>
            </button>
            <br>
        <?php endforeach; ?>
    </div>

</body>
</html>
<?php 
    mysqli_close($conn);
?>