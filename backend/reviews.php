<?php
session_start();
$root_dir = $_SERVER['DOCUMENT_ROOT'];
include($root_dir . '/student008/shop/backend/config/connection.php');
include($root_dir . '/student008/shop/backend/header.php');

// Verificación de Autenticación
if (!isset($_SESSION['user_id'])) {
    header('Location: /student008/shop/login.php'); 
    exit;
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role']; 
$is_admin = ($user_role === 'admin');

// Procesar envío de nueva reseña
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $id_producto = (int)$_POST['id_producto'];
    $valoracion = (int)$_POST['valoracion'];
    $comentario = mysqli_real_escape_string($conn, trim($_POST['comentario']));
    $fecha_resena = date('Y-m-d');
    
    // Validar que el usuario haya comprado el producto
    $sql_check = "SELECT COUNT(*) as comprado 
                  FROM 008_pedido 
                  WHERE id_cliente = $user_id AND id_producto = $id_producto";
    $result_check = mysqli_query($conn, $sql_check);
    $row_check = mysqli_fetch_assoc($result_check);
    
    if ($row_check['comprado'] > 0 || $is_admin) {
        // Insertar reseña
        $sql_insert = "INSERT INTO 008_resena (id_producto, id_cliente, valoracion, comentario, fecha_resena) 
                       VALUES ($id_producto, $user_id, $valoracion, '$comentario', '$fecha_resena')";
        
        if (mysqli_query($conn, $sql_insert)) {
            $mensaje_exito = "Reseña enviada correctamente.";
        } else {
            $mensaje_error = "Error al enviar la reseña: " . mysqli_error($conn);
        }
    } else {
        $mensaje_error = "Debes haber comprado este producto para dejar una reseña.";
    }
}

// Procesar eliminación de reseña (solo admin o el propio usuario)
if (isset($_GET['delete_review'])) {
    $id_resena = (int)$_GET['delete_review'];
    
    // Verificar permisos
    $sql_verify = "SELECT id_cliente FROM 008_resena WHERE id_resena = $id_resena";
    $result_verify = mysqli_query($conn, $sql_verify);
    $row_verify = mysqli_fetch_assoc($result_verify);
    
    if ($is_admin || ($row_verify && $row_verify['id_cliente'] == $user_id)) {
        $sql_delete = "DELETE FROM 008_resena WHERE id_resena = $id_resena";
        if (mysqli_query($conn, $sql_delete)) {
            $mensaje_exito = "Reseña eliminada correctamente.";
        } else {
            $mensaje_error = "Error al eliminar la reseña.";
        }
    } else {
        $mensaje_error = "No tienes permisos para eliminar esta reseña.";
    }
}

// Construcción de la Consulta SQL para mostrar reseñas
$sql_reviews = "
    SELECT 
        r.id_resena,
        r.id_producto,
        r.id_cliente,
        r.valoracion,
        r.comentario,
        r.fecha_resena,
        cl.nombre AS nombre_cliente,
        pr.nombre_producto,
        pr.precio
    FROM 
        008_resena r
    JOIN
        008_cliente cl ON r.id_cliente = cl.id_cliente
    JOIN
        008_producto pr ON r.id_producto = pr.id_producto
";

// Aplicar filtro según el tipo de usuario
if (!$is_admin) {
    // Si NO es admin, mostrar todas las reseñas públicas
    // pero destacar las propias
    $sql_reviews .= " ORDER BY r.fecha_resena DESC";
} else {
    // Admin ve todas las reseñas
    $sql_reviews .= " ORDER BY r.fecha_resena DESC";
}

$result_reviews = mysqli_query($conn, $sql_reviews);

$reviews = [];
if ($result_reviews) {
    $reviews = mysqli_fetch_all($result_reviews, MYSQLI_ASSOC);
    mysqli_free_result($result_reviews);
}

// Obtener productos que el usuario ha comprado (para el formulario)
$sql_productos_comprados = "
    SELECT DISTINCT pr.id_producto, pr.nombre_producto
    FROM 008_pedido p
    JOIN 008_producto pr ON p.id_producto = pr.id_producto
    WHERE p.id_cliente = $user_id
    ORDER BY pr.nombre_producto
";
$result_productos = mysqli_query($conn, $sql_productos_comprados);
$productos_comprados = [];
if ($result_productos) {
    $productos_comprados = mysqli_fetch_all($result_productos, MYSQLI_ASSOC);
    mysqli_free_result($result_productos);
}

// Calcular estadísticas de valoraciones por producto
$sql_stats = "
    SELECT 
        id_producto,
        AVG(valoracion) as promedio,
        COUNT(*) as total_resenas
    FROM 008_resena
    GROUP BY id_producto
";
$result_stats = mysqli_query($conn, $sql_stats);
$stats = [];
while ($row = mysqli_fetch_assoc($result_stats)) {
    $stats[$row['id_producto']] = [
        'promedio' => round($row['promedio'], 1),
        'total' => $row['total_resenas']
    ];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $is_admin ? 'Gestión de Reseñas' : 'Reseñas de Productos' ?></title>
</head>
<body>
    <h1><?= $is_admin ? 'Gestión de Todas las Reseñas' : 'Reseñas de Productos' ?></h1>
    
    <?php if (isset($mensaje_exito)): ?>
        <div class="mensaje mensaje-exito"><?= htmlspecialchars($mensaje_exito) ?></div>
    <?php endif; ?>
    
    <?php if (isset($mensaje_error)): ?>
        <div class="mensaje mensaje-error"><?= htmlspecialchars($mensaje_error) ?></div>
    <?php endif; ?>
    
    <!-- Formulario para nueva reseña -->
    <?php if (!empty($productos_comprados)): ?>
    <div class="review-form">
        <h2>Escribe una nueva reseña</h2>
        <form method="POST">
            <div class="form-group">
                <label for="id_producto">Producto comprado:</label>
                <select name="id_producto" id="id_producto" required>
                    <option value="">Selecciona un producto...</option>
                    <?php foreach ($productos_comprados as $producto): ?>
                        <option value="<?= $producto['id_producto'] ?>">
                            <?= htmlspecialchars($producto['nombre_producto']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Valoración:</label>
                <div class="rating-input">
                    <input type="radio" name="valoracion" value="5" id="star5" required>
                    <label for="star5">★</label>
                    <input type="radio" name="valoracion" value="4" id="star4">
                    <label for="star4">★</label>
                    <input type="radio" name="valoracion" value="3" id="star3">
                    <label for="star3">★</label>
                    <input type="radio" name="valoracion" value="2" id="star2">
                    <label for="star2">★</label>
                    <input type="radio" name="valoracion" value="1" id="star1">
                    <label for="star1">★</label>
                </div>
            </div>
            
            <div class="form-group">
                <label for="comentario">Comentario:</label>
                <textarea name="comentario" id="comentario" 
                          placeholder="Cuéntanos tu experiencia con este producto..." 
                          required></textarea>
            </div>
            
            <button type="submit" name="submit_review" class="btn-submit">Publicar Reseña</button>
        </form>
    </div>
    <?php endif; ?>
    
    <!-- Lista de reseñas -->
    <h2>Reseñas de clientes</h2>
    
    <?php if (empty($reviews)): ?>
        <div class="no-reviews">
            <p>Aún no hay reseñas publicadas.</p>
            <?php if (!empty($productos_comprados)): ?>
                <p>¡Sé el primero en escribir una reseña!</p>
            <?php endif; ?>
        </div>
    <?php else: ?>

    <?php foreach ($reviews as $review): ?>
        <?php 
            $is_own_review = ($review['id_cliente'] == $user_id);
            $producto_stats = isset($stats[$review['id_producto']]) ? $stats[$review['id_producto']] : null;
        ?>
        
        <div class="review-container <?= $is_own_review ? 'own-review' : '' ?>">
            <div class="review-header">
                <div>
                    <div class="review-product">
                        <?= htmlspecialchars($review['nombre_producto']) ?>
                    </div>
                    <div class="review-rating">
                        <?= str_repeat('★', $review['valoracion']) ?>
                        <?= str_repeat('☆', 5 - $review['valoracion']) ?>
                    </div>
                </div>
                
                <?php if ($is_admin || $is_own_review): ?>
                    <a href="?delete_review=<?= $review['id_resena'] ?>" 
                       class="btn-delete"> Eliminar
                    </a>
                <?php endif; ?>
            </div>
            
            <div class="review-info">
                <span class="review-author">
                    <?= $is_admin ? htmlspecialchars($review['nombre_cliente']) : 
                        ($is_own_review ? 'Tú' : htmlspecialchars($review['nombre_cliente'])) ?>
                </span>
                <span class="review-date">
                    <?= date('d/m/Y', strtotime($review['fecha_resena'])) ?>
                </span>
            </div>
            
            <div class="review-comment">
                <?= nl2br(htmlspecialchars($review['comentario'])) ?>
            </div>
            
            <?php if ($producto_stats): ?>
                <div class="review-stats">
                    Este producto tiene <?= $producto_stats['total'] ?> reseña(s) 
                    con una valoración promedio de <?= $producto_stats['promedio'] ?>
                </div>
            <?php endif; ?>
        </div>
        
    <?php endforeach; ?>
    
    <?php endif; ?>

</body>
</html>
<?php 
    mysqli_close($conn);
    include($root_dir . '/student008/shop/backend/footer.php');
?>