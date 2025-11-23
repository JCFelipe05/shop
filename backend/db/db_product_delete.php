<?php
    $root_dir = $_SERVER['DOCUMENT_ROOT'];
    include($root_dir . '/student008/shop/backend/config/connection.php');
    include($root_dir . '/student008/shop/backend/header.php');
    
    print_r ($_POST);
    
    $product_id = $_POST['id'];

    $sql = "DELETE FROM producto WHERE id_producto='$product_id'";
    if (mysqli_query($conn, $sql)) {
        echo "Registro eliminado correctamente.";
    } else {
        echo "Error al borrar el registro.";
    }

    mysqli_close($conn);
    include($root_dir . '/student008/shop/backend/footer.php');
?>