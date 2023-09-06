<?php
    ob_start();
        if(isset($_COOKIE["email"])) {
            header("Location: index.php");
        }
    ob_end_flush();
?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="app/css/styles.css"/>
        <meta name="author" content="Cassie Marquez"/>

        <title>Cassie&lsquo;s Book Inventory App</title>
    </head>
    <body>
        <header>
            <div class="headline">
                <h1 style="text-align: center;">Cassie&lsquo;s Book Inventory App</h1>
            </div>
        </header>
        <section id="container" class="container">
            <fieldset class="reader-config-group">
                <h4>Login</h4>
                <form action="signin.php" method="post">
                    Email: <input type="text" id="email" name="email"><br />
                    Password: <input type="password" id="password" name="password"><br />
                    <input type="submit" id="submit" name="submit" value="Submit">
                </form>
                <p> Don't have a login? <a href="register.php">Register</a> an account.</p>
                <p><a href="reset.php">Forgot your password?</a></p>
            </fieldset>
        </section><!-- /content -->
        <footer>
            <p style="text-align: center;">
                &copy; Cassie Marquez.  For support contact <a href="mailto:dehe2792@gmail.com" style="color: yellow;">Danny Wall</a> by email.
            </p>
        </footer>
    </body>
</html>