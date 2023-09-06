<?php
    ob_start();
    setcookie("userid", '', time() -7000000, '/');
    setcookie("email", '', time() -7000000, '/');
    header("Location: /inventory/login.php");
    ob_end_flush();
?>