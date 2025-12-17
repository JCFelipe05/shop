<?php
if (!isset($_POST['idioma'])) {
    header("Location: /student008/shop/backend/index.php");
    exit();
}

$idiomas_validos = ['es', 'en', 'ca'];
$idioma = $_POST['idioma'];

if (!in_array($idioma, $idiomas_validos)) {
    $idioma = 'es';
}

// Crear la cookie (30 días)
setcookie(
    "idioma",
    $idioma,
    [
        "expires" => time() + (30 * 24 * 60 * 60),
        "path" => "/",
        "httponly" => false,
        "samesite" => "Lax"
    ]
);

// Volver a la página anterior
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
?>