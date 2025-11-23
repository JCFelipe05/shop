<?php 
     
    $root_dir = $_SERVER['DOCUMENT_ROOT'];
    include($root_dir . '/student008/shop/backend/header.php');
?>
<div>
    <form action="/student008/shop/backend/forms/form_product_update" method="POST">
        <label for="id">ID del producto:</label>
        <input type="number" id="id" name="id" required>
    </form>
</div>
<?php include($root_dir . '/student008/shop/backend/footer.php'); ?>