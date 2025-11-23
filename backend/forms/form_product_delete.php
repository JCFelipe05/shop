<?php 
    $root_dir = $_SERVER['DOCUMENT_ROOT'];
    include($root_dir . '/student008/shop/backend/header.php');

    $id = $_POST['id'];
?>
<div>
    <form action="/student008/shop/backend/db/db_product_delete.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <p>¿Estás seguro de que deseas eliminar el producto con ID <?php echo $id; ?>?</p>
        <input type="submit" value="Eliminar">
    </form>
</div>
<?php
    include($root_dir . '/student008/shop/backend/footer.php');
?>