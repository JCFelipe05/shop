<?php

    $root_dir = $_SERVER['DOCUMENT_ROOT'];
    include($root_dir . '/student008/shop/backend/config/connection.php');
    include($root_dir . '/student008/shop/backend/header.php');

    print_r ($_POST);
    
    $id = $_POST['id'];
    $product_name = $_POST['name'];
    $color = $_POST['color'];
    $size = $_POST['size'];
    $description = $_POST['desc'];
    $price = $_POST['price'];

    $sql = "UPDATE 008_producto SET nombre_producto='$product_name', descripcion='$description', color='$color', medida='$size', precio='$price' WHERE id_producto=$id";

    if (mysqli_query($conn, $sql)) {
        echo "Registro actualizado correctamente.";
    } else {
        echo "Error: Conexión fallida.";
    }
    
    mysqli_close($conn);
?>
<?php 
    include($root_dir . '/student008/shop/backend/footer.php');
?>