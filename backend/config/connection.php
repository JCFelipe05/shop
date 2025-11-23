<?php
    // Conexión a la base de datos
    // $servername = "remotehost.es";
    // $username = "dwess1234";
    // $password = "usertest1234.";
    // $dbname = "dwesdatabase";

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "paracordial_db";

    $conn = mysqli_connect($servername, $username, $password, $dbname);

     // Verificar la conexión

    if (!$conn) {
        echo ("Connection error: " . mysqli_connect_error());
    }
?>