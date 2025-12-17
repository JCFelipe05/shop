<?php
$root_dir = $_SERVER['DOCUMENT_ROOT'];
include($root_dir . '/student008/shop/backend/header.php');
?>

<head>
    <link rel="stylesheet" href="/student008/shop/css/form.css">
</head>

<body>
    <form action="../db/db_contact.php" method="POST">
        <label for="email">Tu email:</label><br>
        <input type="email" name="email" required><br><br>

        <label for="mensaje">Tu mensaje:</label><br>
        <textarea name="mensaje" required></textarea><br><br>

        <button type="submit">Enviar</button>
    </form>
</body>
<?php
include($root_dir . '/student008/shop/backend/footer.php');
?>