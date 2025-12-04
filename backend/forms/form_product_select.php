<?php 
     
    $root_dir = $_SERVER['DOCUMENT_ROOT'];
    include($root_dir . '/student008/shop/backend/header.php');
?>
    <div>
        <form action="/student008/shop/backend/db/db_product_select.php" method="POST">
            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" required>
            <br>
            <input type="submit" value="Enviar">
        </form>
    </div>