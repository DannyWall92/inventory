<?php
    ob_start();
    $link = mysqli_connect('localhost', 'dew_app', '1!Spider!1', 'dew_inventoryapp');
        $email = mysqli_real_escape_string($link, htmlspecialchars($_POST['email']));
        $password = $_POST['password'];
        $query = "select ID, email, password from users where email like '$email'";
        $result = mysqli_query($link, $query);
        $row = mysqli_fetch_assoc($result);
        $userid = $row['ID'];
        $email = $row['email'];
        $hash = $row['password'];
        if (password_verify($password, $hash)) {
            setcookie("userid", $userid, time() + (86400 * 30), "/");
            setcookie("email", $email, time() + (86400 * 30), "/");
            header("Location: index.php");    
        } else {
            header("Location: login.php");    
        }
    ob_end_flush();
    //below this, comment out header redirect above for debug purposes
?>
<html>
    <body>
        <?php
            echo ($userid . "<BR />" . $email. "<BR />" . $password. "<BR />" . $hash. "<BR /><br />");
            echo ($query . "<br /><br />");
            echo ($source . " " . $cio . " " . $howmany . " " . $title);
            echo ("<br /><br />");
            var_dump($_REQUEST);
            echo ("<br /><br />");
            var_dump($_POST);
        ?>
    </body>
</html>