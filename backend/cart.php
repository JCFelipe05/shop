<?php
session_start();
$root_dir = $_SERVER['DOCUMENT_ROOT'];
include($root_dir . '/student008/shop/backend/config/connection.php');
include($root_dir . '/student008/shop/backend/header.php');

$sql = "SELECT id_producto, id_cliente, cantidad FROM 008_carrito WHERE id_cliente = " . $_SESSION['user_id'];

$total = 0;

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
    <h1>Tus productos en el carrito</h1>
    </br>
    <div class="contenedor-productos">
        <?php foreach ($products as $product): 
            $sql_product = "SELECT nombre_producto, descripcion, precio FROM 008_producto WHERE id_producto = " . $product['id_producto'];
            $result_product = mysqli_query($conn, $sql_product);
            $product_details = mysqli_fetch_assoc($result_product);

            $subtotal = $product_details['precio'] * $product['cantidad'];
            $total += $product_details['precio'] * $product['cantidad'];
        ?>
            <div class="producto" id="producto-<?= $product['id_producto'] ?>">
                <h3><?= htmlspecialchars($product_details['nombre_producto']) ?></h3>
                <p class="detalle"><?= htmlspecialchars($product_details['descripcion']) ?></p>
                <p class="precio"><?= number_format($product_details['precio'], 2) ?> €</p>

                <p class="cantidad">Cantidad: 
                    <span id="cantidad-<?= $product['id_producto'] ?>">
                        <?= $product['cantidad'] ?>
                    </span>
                </p>
                <p class="subtotal">Subtotal: <?= number_format($subtotal, 2) ?> €</p>

                <button class="btn-delete" data-id="<?= $product['id_producto'] ?>">( - )</button>
            </div>
            </br>
        <?php endforeach; ?>
        <div class="total-carrito">
            <h2>Total: <?= number_format($total, 2) ?> €</h2>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll(".btn-delete").forEach(button => {
                button.addEventListener("click", function () {
                    const productId = this.dataset.id;

                    fetch('/student008/shop/backend/delete_from_cart.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: 'id_producto=' + encodeURIComponent(productId)
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {

                            // Si la cantidad > 1 → actualizamos solamente el número
                            if (data.remaining > 0) {
                                document.getElementById("cantidad-" + productId).textContent = data.remaining;
                            } else {
                                // Si la cantidad llega a 0 → borramos el producto del DOM
                                document.getElementById("producto-" + productId).remove();
                            }

                        } else {
                            alert("Error al eliminar: " + data.message);
                        }
                    })
                    .catch(err => {
                        console.error("Error AJAX:", err);
                    });
                });
            });
        });
    </script>
</body>
</html>
<?php 
    mysqli_close($conn);
?>