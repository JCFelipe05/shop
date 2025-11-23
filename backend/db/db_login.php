<?php
    session_start();
    $root_dir = $_SERVER['DOCUMENT_ROOT'];
    include($root_dir . '/student008/shop/backend/config/connection.php');
    include($root_dir . '/student008/shop/backend/header.php');
    
    print_r ($_POST);
    
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM producto WHERE username ='$username' AND password ='$password'";
    if (mysqli_query($conn, $sql) != null) {
        header("Location: /student008/shop/backend/index.php");
    } else {
        echo "Error al verificar usuario.";
    }

    mysqli_close($conn);
    include($root_dir . '/student008/shop/backend/footer.php');
?>