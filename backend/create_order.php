<?php
session_start();
$root_dir = $_SERVER['DOCUMENT_ROOT'];
include($root_dir . '/student008/shop/backend/config/connection.php');

// Función de redirección para manejar errores o éxito
function redirect_with_message($message, $success = true)
{
    // Almacena el mensaje en la sesión para mostrarlo en la página de destino (ej. cart.php)
    $_SESSION['order_message'] = $message;
    $_SESSION['order_success'] = $success;

    // Redirige al carrito (o a una página de confirmación si existe)
    header('Location: /student008/shop/backend/cart.php');
    exit;
}

if (!isset($_SESSION['user_id'])) {
    redirect_with_message('Error: Usuario no autenticado.', false);
}

if (!isset($_POST['confirm_order'])) {
    // Esto evitaría que se acceda directamente al archivo
    redirect_with_message('Error: Acceso denegado.', false);
}

$user_id = $_SESSION['user_id'];

// Iniciamos la transacción para asegurar la integridad
mysqli_begin_transaction($conn);

try {
    // Obtener el email del cliente
    $sql_email = "SELECT email FROM 008_cliente WHERE id_cliente = ?";
    $stmt_email = mysqli_prepare($conn, $sql_email);
    mysqli_stmt_bind_param($stmt_email, "i", $user_id);
    mysqli_stmt_execute($stmt_email);
    $result_email = mysqli_stmt_get_result($stmt_email);
    $cliente = mysqli_fetch_assoc($result_email);
    mysqli_stmt_close($stmt_email);

    if (!$cliente) {
        throw new Exception("No se pudo obtener el email del cliente.");
    }

    $email_cliente = $cliente['email'];

    // Recuperar productos del carrito y calcular el total
    $sql_cart_details = "
        SELECT 
            c.id_producto, 
            c.cantidad, 
            p.precio 
        FROM 
            008_carrito c
        JOIN 
            008_producto p ON c.id_producto = p.id_producto
        WHERE 
            c.id_cliente = ?
    ";

    $stmt_details = mysqli_prepare($conn, $sql_cart_details);
    mysqli_stmt_bind_param($stmt_details, "i", $user_id);
    mysqli_stmt_execute($stmt_details);
    $result_details = mysqli_stmt_get_result($stmt_details);
    $cart_items = mysqli_fetch_all($result_details, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt_details);

    if (empty($cart_items)) {
        throw new Exception("El carrito está vacío. No se puede realizar el pedido.");
    }

    $total_pedido = 0;
    $fecha_pedido = date('Y-m-d');
    $estado_pedido = 'Pendiente';
    $direccion_envio = 'Dirección de ejemplo';
    $order_id = null;

    // Insertar en 008_pedido (una fila por cada producto) - AHORA INCLUYE EMAIL
    foreach ($cart_items as $item) {
        $subtotal_producto = (float) $item['precio'] * (int) $item['cantidad'];

        $sql_insert_order = "
            INSERT INTO 008_pedido 
                (id_cliente, id_producto, fecha_pedido, total, estado_pedido, direccion_envio, email) 
            VALUES 
                (?, ?, ?, ?, ?, ?, ?)
        ";

        $stmt_insert = mysqli_prepare($conn, $sql_insert_order);
        mysqli_stmt_bind_param($stmt_insert, "iissdss", $user_id, $item['id_producto'], $fecha_pedido, $subtotal_producto, $estado_pedido, $direccion_envio, $email_cliente);

        if (!mysqli_stmt_execute($stmt_insert)) {
            throw new Exception("Fallo al insertar el detalle del pedido: " . mysqli_stmt_error($stmt_insert));
        }

        if ($order_id === null) {
            $order_id = mysqli_insert_id($conn);
        }
        $total_pedido += $subtotal_producto;

        mysqli_stmt_close($stmt_insert);
    }

    // Borrar productos del carrito
    $sql_delete_cart = "DELETE FROM 008_carrito WHERE id_cliente = ?";
    $stmt_delete = mysqli_prepare($conn, $sql_delete_cart);
    mysqli_stmt_bind_param($stmt_delete, "i", $user_id);

    if (!mysqli_stmt_execute($stmt_delete)) {
        throw new Exception("Fallo al limpiar el carrito: " . mysqli_stmt_error($stmt_delete));
    }
    mysqli_stmt_close($stmt_delete);

    // Si todo fue bien, confirmamos la transacción
    mysqli_commit($conn);
    mysqli_close($conn);

    $message = "¡Pedido realizado con éxito! Tu referencia es el ID: {$order_id} (Total: " . number_format($total_pedido, 2) . " €)";
    redirect_with_message($message, true);

} catch (Exception $e) {
    // Si algo falló, revertimos todos los cambios
    mysqli_rollback($conn);
    mysqli_close($conn);
    redirect_with_message("Error al realizar el pedido: " . $e->getMessage(), false);
}
?>