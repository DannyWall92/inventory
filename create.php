<?php
    ob_start();
        $link = mysqli_connect('localhost', 'dew_app', '1!Spider!1', 'dew_inventoryapp');
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $email = mysqli_real_escape_string($link, htmlspecialchars($_POST['email']));
        $school = mysqli_real_escape_string($link, htmlspecialchars($_POST['school']));
        $query = "insert into users (password, email, school) VALUES ('$password', '$email', '$school')";
        $result = mysqli_query($link, $query);
        $query = "select ID from users where email like '$email' and password like '$password'";
        $result = mysqli_query($link, $query);
        $row = mysqli_fetch_assoc($result);
        $userID = $row['ID'];
        setcookie("userid", $userID, time() + (86400 * 30));
        setcookie("email", $email, time() + (86400 * 30));
        header("Location: index.php");
    ob_end_flush();
?>