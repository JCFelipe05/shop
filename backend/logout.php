<?php 
    session_start();
    session_unset();
    session_destroy();
    header("Location: /student008/shop/backend/forms/form_login.php");
    exit();
?>