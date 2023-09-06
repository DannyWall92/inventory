<?php
    ob_start();
        if(!isset($_COOKIE["userid"])) {
            header("Location: ../login.php");
        } else {
            $userid = $_COOKIE["userid"];
        }
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
    ob_end_flush();
?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="app/css/styles.css"/>
        <meta name="author" content="Cassie Marquez"/>
        <title>Inventory Display</title>
    </head>
    <body>
    <header>
            <div class="headline">
                <h1 style="text-align:center;">Quick Inventory View</h1>
                <p><a href="index.php">Return to main menu</a></p>
            </div>
        </header>
        <section id="container" class="container">
            <p>Simple inventory list, for robust filter and search download the inventory and import into Excel.</p>
            <p><a href="view.php?filter=no">Show All Records</a></p>
            <form action="view.php" method="GET">
                Title/Author/Publisher Search: <input type="text" name="search" id="search" /> <input type="submit" name="submit" id="submit" value="Submit" />
            </form>
            <table border="1" cellpadding="5">
                <tr>
                    <th><a href="view.php?s=isbn">ISBN</a></th>
                    <th><a href="view.php?s=title">Title</a></th>
                    <th><a href="view.php?s=authors">Authors</a></th>
                    <th><a href="view.php?s=publisher">Publisher</a></th>
                    <th><a href="view.php?s=quantity">Qty</a></th>
                </tr>
                <?php
                    $link = mysqli_connect('localhost', 'dew_app', '1!Spider!1', 'dew_inventoryapp');
                    if (!isset($_GET['s'])) {
                        $query = "select * from inventory where UserID = $userid and quantity > 0";
                    } else {
                        $s = mysqli_real_escape_string($link, htmlspecialchars($_GET['s']));
                        $query = "select * from inventory where UserID = $userid and quantity > 0 order by $s";
                    }
                    if (isset($_GET['filter'])){
                        $query = "select * from inventory where UserID = $userid";
                    }
                    if (isset($_GET['search'])) {
                        $search = mysqli_real_escape_string($link, htmlspecialchars($_GET['search']));
                        $query = "select * from inventory where UserID = $userid and quantity > 0 and title like '%$search%' or authors like '%$search%' or publisher like '%$search%'";
                    }
                
                    $result = mysqli_query ($link, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                        $isbn = $row['isbn'];
                        $title = $row['title'];
                        $authors = $row['authors'];
                        $publisher = $row['publisher'];
                        $quantity = $row['quantity'];
                        echo ("<tr><td><a href='app/cio_enter.php?isbn=$isbn'>$isbn</a></td>");
                        echo ("<td>$title</td><td>$authors</td><td>$publisher</td><td>$quantity</td></tr>");
                    }
                ?>
            </table>
        </section>
    </body>
</html>
