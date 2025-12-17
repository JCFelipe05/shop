<?php
session_start();
// Configuración de conexión
$root_dir = $_SERVER['DOCUMENT_ROOT'];
include($root_dir . '/student008/shop/backend/config/connection.php');
include($root_dir . '/student008/shop/backend/header.php');

$esAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

$sql = "SELECT id_producto, nombre_producto, descripcion, precio FROM 008_producto";

$result = mysqli_query($conn, $sql);

$products = [];

if ($result) {
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
    <h2>Buscar productos</h2>
    <input type="text" id="buscador" placeholder="Buscar producto..." autocomplete="off">
    <div id="resultados"></div>
    <br>

    <?php if ($esAdmin): ?>
        <button>
            <a href="/student008/shop/backend/forms/form_product_insert.php">
                Añadir producto
            </a>
        </button>
    <?php endif; ?>
    <div class="contenedor-productos">
        <?php foreach ($products as $product): ?>
            <div class="producto">
                <h3><?= htmlspecialchars($product['nombre_producto']) ?></h3>
                <p class="detalle"><?= htmlspecialchars($product['descripcion']) ?></p>
                <p class="precio"><?= number_format($product['precio'], 2) ?> €</p>
            </div>

            <!-- Botón visible para todos -->
            <button>
                <a href="/student008/shop/backend/cart_insert.php?id=<?= $product['id_producto'] ?>">
                    Añadir al carrito
                </a>
            </button>

            <!-- SOLO ADMIN -->
            <?php if ($esAdmin): ?>
                <button class="btn btn-secondary btn-small">
                    <a href="/student008/shop/backend/forms/form_product_update.php?id=<?= $product['id_producto'] ?>">
                        Update
                    </a>
                </button>

                <button>
                    <a href="/student008/shop/backend/db/db_product_delete.php?id=<?= $product['id_producto'] ?>">
                        Eliminar
                    </a>
                </button>
            <?php endif; ?>

            <br>
        <?php endforeach; ?>
    </div>
    <script>
        document.getElementById("buscador").addEventListener("keyup", function () {
            let texto = this.value;

            if (texto.length === 0) {
                document.getElementById("resultados").innerHTML = "";
                return;
            }

            let xhr = new XMLHttpRequest();
            xhr.open("GET", "/student008/shop/backend/search_products.php?q=" + encodeURIComponent(texto), true);

            xhr.onload = function () {
                if (this.status === 200) {
                    document.getElementById("resultados").innerHTML = this.responseText;
                }
            };

            xhr.send();
        });
    </script>
</body>

</html>
<?php
mysqli_close($conn);
include($root_dir . '/student008/shop/backend/footer.php');
?>