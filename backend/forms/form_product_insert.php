<?php

$root_dir = $_SERVER['DOCUMENT_ROOT'];
include($root_dir . '/student008/shop/backend/header.php');
?>

<head>
    <link rel="stylesheet" href="/student008/shop/css/form.css">
</head>

<body>
    <div>
        <form action="/student008/shop/backend/db/db_product_insert.php" method="POST">
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
            <label for="category">Categoría:</label>
            <select name="category" id="category">
                <option value="1">Material</option>
                <option value="2">Accesorio</option>
            </select>
            <br>
            <input type="submit" value="Enviar">
        </form>
    </div>
</body>
<?php
include($root_dir . '/student008/shop/backend/footer.php');
?>