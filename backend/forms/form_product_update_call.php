<?php 
     
    $root_dir = $_SERVER['DOCUMENT_ROOT'];
    include($root_dir . '/student008/shop/backend/header.php');
?>
<div>
    <form action="/student008/shop/backend/forms/form_product_update.php" method="POST">
        <label for="id">ID del producto:</label>
        <input type="number" id="id" name="id" required>
        <br>
        <button type="submit">Actualizar Producto</button>
    </form>
</div>
