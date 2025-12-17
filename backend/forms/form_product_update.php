<?php

$root_dir = $_SERVER['DOCUMENT_ROOT'];
include($root_dir . '/student008/shop/backend/header.php');

$id = $_GET['id'];
?>

<head>
    <link rel="stylesheet" href="/student008/shop/css/form.css">
</head>

<body>
    <div>
        <form action="/student008/shop/backend/db/db_product_update.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <label for="name">Nombre del producto:</label>
            <input type="text" id="name" name="name" required>
            <br>
            <label for="desc">Descripción:</label>
            <input type="text" id="desc" name="desc" required>
            <br>
            <label for="color">Color:</label>
            <input type="text" id="color" name="color" required>
            <br>
            <label for="size">Medida:</label>
            <input type="text" id="size" name="size" required>
            <br>
            <label for="price">Precio:</label>
            <input type="number" id="price" name="price" required>
            <br>
            <input type="submit" value="Actualizar">
        </form>
    </div>
</body>
<?php
include($root_dir . '/student008/shop/backend/footer.php');
?>