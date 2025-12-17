<?php
session_start();

$root_dir = $_SERVER['DOCUMENT_ROOT'];
include($root_dir . '/student008/shop/backend/config/connection.php');

$id = (int) $_POST['id_cliente'];

// if (!isset($_SESSION['user_id'])) {
//     exit("Acceso denegado");
// }

// if ($_SESSION['role'] !== 'admin' && $_SESSION['user_id'] !== $id) {
//     exit("Acceso denegado");
// }

// Recoger datos
$nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
$tipo = mysqli_real_escape_string($conn, $_POST['tipo']);

$sql = "UPDATE 008_cliente 
        SET nombre = '$nombre',
            email = '$email',
            telefono = '$telefono',
            tipo = '$tipo'
        WHERE id_cliente = $id";

if (mysqli_query($conn, $sql)) {
    header("Location: /student008/shop/backend/clientes.php");
    exit();
} else {
    echo "Error al actualizar: " . mysqli_error($conn);
}

mysqli_close($conn);
?>