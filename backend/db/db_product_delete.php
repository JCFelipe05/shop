<?php
    $root_dir = $_SERVER['DOCUMENT_ROOT'];
    include($root_dir . '/student008/shop/backend/config/connection.php');
    include($root_dir . '/student008/shop/backend/header.php');
    
    $product_id = $_GET['id'];

    $sql = "DELETE FROM 008_producto WHERE id_producto='$product_id'";
    if (!mysqli_query($conn, $sql)) {
        echo "No se puede eliminar el producto porque tiene pedidos asociados.";
    } else {
        echo "Producto eliminado correctamente.";
    }

    mysqli_close($conn);
?>