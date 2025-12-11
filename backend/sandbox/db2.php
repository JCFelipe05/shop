<?php
    $root_dir = $_SERVER['DOCUMENT_ROOT'];
    include($root_dir . '/student008/shop/backend/config/connection.php');
?>

<?php

    $text = $_GET["text"];
    $sql = "SELECT nombre_producto FROM 008_producto WHERE nombre_producto LIKE '%$text%'";
    $result = mysqli_query($conn, $sql);
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $products_json = json_encode($products);
    echo $products_json;
    mysqli_close($conn);

?>