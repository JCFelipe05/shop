<?php
    // Configuración y Sesión
    session_start();
    $root_dir = $_SERVER['DOCUMENT_ROOT'];
    
    include($root_dir . '/student008/shop/backend/config/connection.php'); 
    include($root_dir . '/student008/shop/backend/header.php');

    // Verificación de Sesión
    if (!isset($_SESSION['user_id'])) {
        header("Location: /student008/shop/backend/forms/form_login.php"); 
        exit();
    }

    // Determinar y construir la consulta SQL
    if ($_SESSION['role'] == 'admin') {
        // ADMIN: Ve todos los clientes
        $sql = "SELECT id_cliente, nombre, email, telefono, fecha_registro, tipo FROM 008_cliente";
    } else {
        // NO-ADMIN: Solo ve su propio perfil
        $safe_user_id = (int) $_SESSION['user_id'];
        
        $sql = "SELECT id_cliente, nombre, email, telefono, fecha_registro, tipo FROM 008_cliente WHERE id_cliente = $safe_user_id";
    }

    // Ejecutar la consulta
    $result = mysqli_query($conn, $sql);
?>

<h2>Clientes Registrados</h2>

<?php if ($result && mysqli_num_rows($result) > 0): ?>
    
    <table border='1'>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Registro</th>
                <th>Tipo</th>
            </tr>
        </thead>
        <tbody>
            
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id_cliente']) ?></td>
                    <td><?= htmlspecialchars($row['nombre']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['telefono']) ?></td>
                    <td><?= htmlspecialchars($row['fecha_registro']) ?></td>
                    <td><?= htmlspecialchars($row['tipo']) ?></td>
                </tr>
            <?php endwhile; ?>
            
        </tbody>
    </table>

<?php elseif ($result): ?>
    <p>No se encontraron clientes.</p>

<?php else: ?>
    <p>Error al ejecutar la consulta: <?= mysqli_error($conn) ?></p>
    
<?php endif; ?>

<?php 
    // Liberar el resultado si existe
    if (isset($result) && is_object($result)) {
        mysqli_free_result($result);
    }
    
    // Cerrar la conexión y cargar el pie de página
    mysqli_close($conn);
    include($root_dir . '/student008/shop/backend/footer.php');
?>