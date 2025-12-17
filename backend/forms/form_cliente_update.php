<?php
session_start();

$root_dir = $_SERVER['DOCUMENT_ROOT'];
include($root_dir . '/student008/shop/backend/config/connection.php');
include($root_dir . '/student008/shop/backend/header.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: /student008/shop/backend/forms/form_login.php");
    exit();
}

$id = (int) $_GET['id'];



// Obtener datos del cliente
$sql = "SELECT nombre, email, telefono, tipo 
        FROM 008_cliente 
        WHERE id_cliente = $id";

$result = mysqli_query($conn, $sql);
$cliente = mysqli_fetch_assoc($result);

if (!$cliente) {
    exit("Cliente no encontrado");
}
?>

<head>
    <link rel="stylesheet" href="/student008/shop/css/form.css">
</head>

<body>
    <h2>Editar cliente</h2>

    <form action="/student008/shop/backend/db/db_cliente_update.php" method="POST">
        <input type="hidden" name="id_cliente" value="<?= $id ?>">

        <label>Nombre:</label><br>
        <input type="text" name="nombre" value="<?= htmlspecialchars($cliente['nombre']) ?>" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="<?= htmlspecialchars($cliente['email']) ?>" required><br><br>

        <label>Teléfono:</label><br>
        <input type="text" name="telefono" value="<?= htmlspecialchars($cliente['telefono']) ?>"><br><br>

        <?php if ($_SESSION['role'] === 'admin'): ?>
            <label>Tipo de usuario:</label><br>
            <select name="tipo">
                <option value="admin" <?= $cliente['tipo'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="customer" <?= $cliente['tipo'] === 'customer' ? 'selected' : '' ?>>Customer</option>
                <option value="guest" <?= $cliente['tipo'] === 'guest' ? 'selected' : '' ?>>Guest</option>
            </select><br><br>
        <?php else: ?>
            <!-- Usuario normal NO puede cambiar su rol -->
            <input type="hidden" name="tipo" value="<?= htmlspecialchars($cliente['tipo']) ?>">
        <?php endif; ?>

        <button type="submit">Guardar cambios</button>
    </form>
</body>

<?php
mysqli_close($conn);
include($root_dir . '/student008/shop/backend/footer.php');
?>