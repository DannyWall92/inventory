<?php
    ob_start();
        if(!isset($_COOKIE["email"])) {
            header("Location: login.php");
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
                <h1 style="text-align:center;">Cassie&lsquo;s Book Inventory App</h1>
            </div>
        </header>
        
        <div id="container" class="container">
            <div class="menu">
                <p class="mainmenu"><a href="app/file_input.php" >Check In/Out - Scan</a></p>
                <p class="mainmenu"><a href="app/cio_enter.php">Check In/Out - Key Enter</a></p>
                <p class="mainmenu"><a href="view.php">View Inventory</a></p>
                <p class="mainmenu"><a href="dl.php">Download Inventory</a></p>
                <p class="mainmenu"><a href="help.html">Instructions</a></p>
                <p class="mainmenu"><a href="logout.php">Logout</a></p>
            </div>
        </div><!-- /content -->
        
        <footer>
            <p style="text-align: center;">
                &copy; Cassie Marquez. All rights Reserved. <br />For support contact <a href="mailto:dehe2792@gmail.com" style="color: yellow;">Danny Wall</a> by email.
            </p>
        </footer>
    </body>
</html>