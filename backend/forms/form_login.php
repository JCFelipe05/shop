<!-- Formulario de login -->
<?php
$root_dir = $_SERVER['DOCUMENT_ROOT'];
include($root_dir . '/student008/shop/backend/header.php');
?>

<head>
    <link rel="stylesheet" href="/student008/shop/css/form.css">
</head>

<body>
    <form action="../db/db_login.php" method="POST">
        <label for="username">Usuario:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Iniciar Sesión</button>
    </form>
</body>
<?php
include($root_dir . '/student008/shop/backend/footer.php');
?>