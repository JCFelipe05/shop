<?php
    $root_dir = $_SERVER['DOCUMENT_ROOT'];
    include($root_dir . '/student008/shop/backend/config/connection.php');

    $text = $_GET['productname'];
    // echo $text;

    $sql = "SELECT * FROM `008_producto` WHERE nombre_producto LIKE '%$text%'";
    $result = mysqli_query($conn, $sql);
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $products_json = json_encode($products);

    echo $products_json;
    mysqli_close($conn);
?>