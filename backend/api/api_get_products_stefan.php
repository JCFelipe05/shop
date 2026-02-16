<?php
session_start();
$root_dir = $_SERVER['DOCUMENT_ROOT'];
include($root_dir . '/student008/shop/backend/config/connection.php');

// Solo admin puede importar
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Solo administradores pueden importar productos");
}

// URL de la API del supplier
$api_url = "https://remotehost.es/student006/shop/backend/api/api_send_products.php?api_key=3333";

// Obtener productos de la API
$productos = @file_get_contents($api_url);
if (!$productos) {
    die("Error: No se pudo conectar con la API");
}

$productos = json_decode($productos, true);

// Verificar formato de respuesta
if (!isset($productos['products']) || !$productos['success']) {
    die("Error: Formato de respuesta inválido");
}

$productos = $productos['products'];

if (empty($productos)) {
    die("No hay productos para importar");
}

// Contadores
$importados = 0;
$actualizados = 0;

// Procesar cada producto
foreach ($productos as $p) {
    $id = (int)$p['videogame_id'];
    $nombre = $p['title'];
    $precio = (float)$p['price'];
    
    $nombre = mysqli_real_escape_string($conn, $nombre);
    $precio = floatval($precio);
    
    // Verificar si existe
    $existe = mysqli_query($conn, "SELECT id_producto FROM 008_producto WHERE nombre_producto = '$nombre' AND supplier_id = '2'");
    
    if (mysqli_num_rows($existe) > 0) {
        // Actualizar precio
        mysqli_query($conn, "UPDATE 008_producto SET precio = '$precio' WHERE nombre_producto = '$nombre'");
        $actualizados++;
    } else {
        // Insertar nuevo
        $sql = "INSERT INTO 008_producto (nombre_producto, descripcion, color, medida, precio, supplier_id, supplier_product_id) 
                VALUES ('$nombre', 'Producto del supplier', 'Varios', 'Estándar', '$precio', '2', '$id')";
        mysqli_query($conn, $sql);
        $importados++;
    }
    
    mysqli_free_result($existe);
}

mysqli_close($conn);

// Mostrar resultado
echo "<h2>Importación Completada</h2>";
echo "<p>Productos nuevos: <strong>$importados</strong></p>";
echo "<p>Productos actualizados: <strong>$actualizados</strong></p>";
?>