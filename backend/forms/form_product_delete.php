<?php
$root_dir = $_SERVER['DOCUMENT_ROOT'];
include($root_dir . '/student008/shop/backend/header.php');

$id = $_POST['id'];
?>

<head>
    <link rel="stylesheet" href="/student008/shop/css/form.css">
</head>
<body>
    <div>
        <form action="/student008/shop/backend/db/db_product_delete.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <p>¿Estás seguro de que deseas eliminar el producto con ID <?php echo $id; ?>?</p>
            <input type="submit" value="Eliminar">
        </form>
    </div>
</body>
<?php
include($root_dir . '/student008/shop/backend/footer.php');
?>