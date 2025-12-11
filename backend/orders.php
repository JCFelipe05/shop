<?php
session_start();
$root_dir = $_SERVER['DOCUMENT_ROOT'];
include($root_dir . '/student008/shop/backend/config/connection.php');
include($root_dir . '/student008/shop/backend/header.php');

// Verificación de Autenticación
if (!isset($_SESSION['user_id'])) {
    // Es mejor redirigir a una página de inicio de sesión
    header('Location: /student008/shop/login.php'); 
    exit;
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role']; 
$is_admin = ($user_role === 'admin');

// Construcción de la Consulta SQL
$sql_orders = "
    SELECT 
        p.id_pedido, 
        p.id_cliente, 
        p.fecha_pedido, 
        p.total, 
        p.estado_pedido, 
        p.direccion_envio,
        cl.nombre AS nombre_cliente,
        pr.nombre_producto,
        pr.precio
    FROM 
        008_pedido p
    JOIN
        008_cliente cl ON p.id_cliente = cl.id_cliente
    JOIN
        008_producto pr ON p.id_producto = pr.id_producto
";

// Aplicar filtro según el tipo de usuario
if (!$is_admin) {
    // Si NO es admin, solo mostramos sus pedidos
    // Usamos $user_id (que es id_cliente) en la cláusula WHERE
    $sql_orders .= " WHERE p.id_cliente = " . $user_id;
}

$sql_orders .= " ORDER BY p.id_pedido DESC"; 

$result_orders = mysqli_query($conn, $sql_orders);

$orders = [];
if ($result_orders) {
    $orders = mysqli_fetch_all($result_orders, MYSQLI_ASSOC);
    mysqli_free_result($result_orders);
}

// Agrupar los pedidos (ya que 008_pedido tiene una fila por producto)
$grouped_orders = [];
foreach ($orders as $order) {
    $order_id = $order['id_pedido'];
    $subtotal_producto = (float)$order['total'];
    $precio_unidad = (float)$order['precio'];
    
    // Cálculo de la cantidad (total / precio)
    $cantidad = ($precio_unidad > 0) ? round($subtotal_producto / $precio_unidad) : 1;
    
    if (!isset($grouped_orders[$order_id])) {
        // Inicializar la cabecera del pedido
        $grouped_orders[$order_id] = [
            'id_pedido' => $order_id,
            'fecha_pedido' => $order['fecha_pedido'],
            'cliente' => $order['nombre_cliente'],
            'estado' => $order['estado_pedido'],
            'total_general' => 0, 
            'detalles' => []
        ];
    }
    
    // Sumar el subtotal al total general
    $grouped_orders[$order_id]['total_general'] += $subtotal_producto;

    // Agregar el detalle del producto
    $grouped_orders[$order_id]['detalles'][] = [
        'nombre_producto' => $order['nombre_producto'],
        'cantidad' => $cantidad,
        'subtotal' => $subtotal_producto
    ];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $is_admin ? 'Gestión de Pedidos' : 'Mis Pedidos' ?></title>
    </head>
<body>
    <h1><?= $is_admin ? 'Gestión de Todos los Pedidos' : 'Mis Pedidos' ?></h1>
    
    <?php if (empty($grouped_orders)): ?>
        <p>Aún no hay pedidos registrados.</p>
    <?php else: ?>

    <?php foreach ($grouped_orders as $pedido): ?>
        
        <div class="order-container">
            
            <h3>Pedido #<?= htmlspecialchars($pedido['id_pedido']) ?> 📦</h3>
            
            <p style="margin-bottom: 5px;">
                <?php if ($is_admin): ?>
                    **Cliente:** **<?= htmlspecialchars($pedido['cliente']) ?>** | 
                <?php endif; ?>
                **Fecha:** <?= htmlspecialchars($pedido['fecha_pedido']) ?> | 
                **Estado:** <span class="<?= ($pedido['estado'] == 'Pendiente') ? 'status-pendiente' : 'status-otro' ?>"><?= htmlspecialchars($pedido['estado']) ?></span>
            </p>
            
            <table border="1">
                <thead>
                    <tr class="table-header">
                        <th>Producto</th>
                        <th class="text-center">Cant.</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedido['detalles'] as $detalle): ?>
                    <tr>
                        <td><?= htmlspecialchars($detalle['nombre_producto']) ?></td>
                        <td class="text-center"><?= htmlspecialchars($detalle['cantidad']) ?></td>
                        <td class="text-right"><?= number_format($detalle['subtotal'], 2) ?> €</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="2" class="text-right">TOTAL FINAL:</td>
                        <td class="text-right"><?= number_format($pedido['total_general'], 2) ?> €</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
    <?php endforeach; ?>
    
    <?php endif; ?>

</body>
</html>
<?php 
    mysqli_close($conn);
    include($root_dir . '/student008/shop/backend/footer.php');
?>