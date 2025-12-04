<?php

    $root_dir = $_SERVER['DOCUMENT_ROOT'];
    include($root_dir . '/student008/shop/backend/config/connection.php');
    include($root_dir . '/student008/shop/backend/header.php');

    print_r ($_POST);
    
    $product_name = $_POST['name'];

    $sql = "SELECT * FROM producto WHERE nombre_producto='$product_name'";
    $result = mysqli_query($conn, $sql);
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
    print_r($rows);

    mysqli_close($conn);
?>