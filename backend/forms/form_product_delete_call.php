<?php 
    $root_dir = $_SERVER['DOCUMENT_ROOT'];
    include($root_dir . '/student008/shop/backend/header.php');
?>
<div>
    <form action="/student008/shop/backend/forms/form_product_delete.php" method="POST">
        <label for="id">ID del producto:</label>
        <input type="text" id="id" name="id" required>
        <br>
        <input type="submit" value="Enviar">
    </form>
</div>