<?php
    session_start();
    $root_dir = $_SERVER['DOCUMENT_ROOT'];
    include($root_dir . '/student008/shop/backend/config/connection.php');
    include($root_dir . '/student008/shop/backend/header.php');

    if (isset($_SESSION['user_id'])) {
        // Verifica el rol por si el admin debe ir a backend/index.php
        if ($_SESSION['role'] == 'admin') {
            header("Location: /student008/shop/backend/index.php");
        } else {
            header("Location: /student008/shop/index.html");
        }
        exit(); // Detenemos la ejecución del resto del script
    }
?>
<?php
    
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM 008_cliente WHERE nombre ='$username' AND password ='$password'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            $_SESSION['user_id'] = $user['id_cliente'];
            $_SESSION['username'] = $user['nombre'];
            $_SESSION['role'] = $user['tipo'];

            echo "¡Bienvenido " . htmlspecialchars($user['nombre']) . "!";

            if($_SESSION['role'] == 'admin'){
                header("Location: /student008/shop/backend/index.php");
            } else {
                header("Location: /student008/shop/index.html");
            }
            exit();
        } else {
            echo "Nombre de usuario o contraseña incorrectos.";
        }
    } else {
        echo "Error al hacer la consulta: " . mysqli_error($conn);
    }

    mysqli_close($conn);
?>
<?php include($root_dir . '/student008/shop/backend/footer.php'); ?>