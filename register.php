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

        <title>Cassie&lsquo;;s Book Inventory App</title>

    </head>
    <body>

        <header>
            <div class="headline">
                <h1 style="text-align: center;">Cassie&lsquo;s Book Inventory App</h1>
            </div>
        </header>
        
        <section id="container" class="container">
            <p>By creating an account and using this app/site you agree to our terms of service, privacy policy, and cookie policy</p>
            <fieldset class="reader-config-group">
                <h4>Create Your Account</h4>
                <form action="create.php" method="post">
                    Email: <input type="text" id="email" name="email"><br />
                    School: <input type="text" id="school" name="school"><br />
                    Password: <input type="text" id="password" name="password"><br />
                    <input type="submit" id="submit" name="submit" value="Submit">
                </form>
                <p>This app is provided only for use by educational institutions.  You MUST use a .edu domain name email address.</p>
            </fieldset>
        </section><!-- /content -->
        
        <footer>
            <p style="text-align: center;">
                &copy; Cassie Marquez.  For support contact <a href="mailto:dehe2792@gmail.com" style="color: yellow;">Danny Wall</a> by email.
            </p>
        </footer>
    </body>
</html>