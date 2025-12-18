<?php
    session_start();
    $root_dir = $_SERVER['DOCUMENT_ROOT'];
    include($root_dir . '/student008/shop/backend/config/connection.php');
    include($root_dir . '/student008/shop/backend/header.php');

    if  (!isset($_SESSION['user_id'])) {
        header("Location: /student008/shop/backend/forms/form_login.php");
        exit();
    }
    
    $product_id = $_GET['id'];

    $sql_select = "SELECT * FROM 008_carrito WHERE id_producto = '$product_id'";;
    if ($result = mysqli_query($conn, $sql_select)) {
        $item = mysqli_fetch_assoc($result);
        if ($item) {
            // Si el producto ya está en el carrito, actualizar la cantidad
            $new_quantity = $item['cantidad'] + 1;
            $update_sql = "UPDATE 008_carrito SET cantidad = '$new_quantity' WHERE id_producto = '$product_id'";
            if (mysqli_query($conn, $update_sql)) {
                if (!($_SESSION['role'] == 'admin')) {
                    header("Location: /student008/shop/index.html");
                } else {
                    header("Location: /student008/shop/backend/products.php");
                }
            } else {
                echo "Error al actualizar la cantidad.";
            }
            mysqli_free_result($result);
            mysqli_close($conn);
            exit();
        } else {
            // Si el producto no está en el carrito, insertarlo
            $sql = "INSERT INTO 008_carrito (id_producto, id_cliente, cantidad) 
                VALUES ('$product_id', '" . $_SESSION['user_id'] . "', '1')";
            if (mysqli_query($conn, $sql)) {
                if (!($_SESSION['role'] == 'admin')) {
                    header("Location: /student008/shop/index.html");
                } else {
                    header("Location: /student008/shop/backend/products.php");
                }
            } else {
                echo "Error al añadir al carrito.";
            }
        }
        mysqli_free_result($result);
    }
    mysqli_close($conn);
?>