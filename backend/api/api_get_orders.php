<?php
session_start();
$root_dir = $_SERVER['DOCUMENT_ROOT'];
include($root_dir . '/student008/shop/backend/config/connection.php');

// Solo admin puede importar
// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//     die("Solo administradores pueden importar productos");
// }

// Recibir datos JSON
$json = file_get_contents('http://localhost/student008/shop/backend/api/');
$data = json_decode($json, true);

// Acceder a los datos
echo $data['email'];
?>