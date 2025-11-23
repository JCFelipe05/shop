<!-- Formulario de login -->
<?php 
    $root_dir = $_SERVER['DOCUMENT_ROOT'];
    include($root_dir . '/student008/shop/backend/header.php');
?>
<form action="/backend/db/db_login.php" method="post">
    <label for="username">Usuario:</label>
    <input type="text" id="username" name="username" required>
    <br>
    <label for="password">Contraseña:</label>
    <input type="password" id="password" name="password" required>
    <button type="submit">Iniciar Sesión</button>
</form>
<?php include($root_dir . '/student008/shop/backend/footer.php'); ?>