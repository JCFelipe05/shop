<?php
session_start();

$root_dir = $_SERVER['DOCUMENT_ROOT'];
include($root_dir . '/student008/shop/backend/config/connection.php');

if (!isset($_SESSION['user_id'])) {
    echo "Debes iniciar sesión para enviar un mensaje de soporte.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id_cliente = $_SESSION['user_id'];
    
    // Se recogen los datos del formulario
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mensaje = mysqli_real_escape_string($conn, $_POST['mensaje']);

    // Insertar en la tabla
    $sql = "INSERT INTO 008_soporte (id_cliente, email, mensaje) 
            VALUES ('$id_cliente', '$email', '$mensaje')";
    
    if (mysqli_query($conn, $sql)) {
        echo "Tu mensaje ha sido enviado correctamente.";
        header("Location: /student008/shop/backend/index.php");
        exit();
    } else {
        echo "Error al guardar el mensaje: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>