<?php
session_start();

$root_dir = $_SERVER['DOCUMENT_ROOT'];
include($root_dir . '/student008/shop/backend/config/connection.php');

if (!isset($_GET['q']) || trim($_GET['q']) === '') {
    exit();
}

$q = mysqli_real_escape_string($conn, $_GET['q']);

// Comprobamos rol
$esAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

// Consulta SOLO nombre + id
$sql = "SELECT id_producto, nombre_producto
        FROM 008_producto
        WHERE nombre_producto LIKE '%$q%'
        LIMIT 5";

$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    while ($product = mysqli_fetch_assoc($result)) {
        ?>
        <div class="producto-buscador">
            <strong><?= htmlspecialchars($product['nombre_producto']) ?></strong>

            <!-- Botones -->
            <a href="/student008/shop/backend/cart_insert.php?id=<?= $product['id_producto'] ?>">
                <button>Añadir al carrito</button>
            </a>

            <?php if ($esAdmin): ?>
                <a href="/student008/shop/backend/forms/form_product_update_call.php?id=<?= $product['id_producto'] ?>">
                    <button>Update</button>
                </a>

                <a href="/student008/shop/backend/db/db_product_delete.php?id=<?= $product['id_producto'] ?>">
                    <button>Eliminar</button>
                </a>
            <?php endif; ?>
        </div>
        <hr>
        <?php
    }
} else {
    echo "<p>No hay resultados</p>";
}

mysqli_close($conn);
?>