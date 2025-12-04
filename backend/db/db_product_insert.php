<?php

    $root_dir = $_SERVER['DOCUMENT_ROOT'];
    include($root_dir . '/student008/shop/backend/config/connection.php');
    include($root_dir . '/student008/shop/backend/header.php');

    print_r ($_POST);
    
    $product_name = $_POST['name'];
    $color = $_POST['color'];
    $size = $_POST['size'];
    $description = $_POST['desc'];
    $price = $_POST['price'];

    $sql = "INSERT INTO producto (nombre_producto, descripcion, color, medida, precio) VALUES ('$product_name', '$description', '$color', '$size', '$price')";

    if (mysqli_query($conn, $sql)) {
        echo "Nuevo registro creado correctamente";
    } else {
        echo "Error: Conexión fallida.";
    }
    mysqli_close($conn);
?>