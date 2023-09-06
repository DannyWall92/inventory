<?php
    ob_start();
        $userid = $_COOKIE["userid"];
        $link = mysqli_connect('localhost', 'dew_app', '1!Spider!1', 'dew_inventoryapp');
    
        // grab post variables
        $source = mysqli_real_escape_string($link, htmlspecialchars($_POST['source']));
        $howmany = mysqli_real_escape_string($link, htmlspecialchars($_POST['howmany']));
        $isbn = mysqli_real_escape_string($link, htmlspecialchars($_POST['isbn']));
        $title = mysqli_real_escape_string($link, htmlspecialchars($_POST['title']));
        $authors = mysqli_real_escape_string($link, htmlspecialchars($_POST['authors']));
        $publisher = mysqli_real_escape_string($link, htmlspecialchars($_POST['publisher']));
        $cio = mysqli_real_escape_string($link, htmlspecialchars($_POST['cio']));

        

        // Check In or Out the book
        if ($cio == "checkin") {
            // Find if the book exists if so then obtain current qty, add, then update else insert
            $query = "select quantity from inventory where isbn = $isbn";
            $result = mysqli_query($link, $query);
            if (mysqli_num_rows($result) < 1) {
                $query = "insert into inventory (isbn, source, title, authors, publisher, quantity, UserID) VALUES ($isbn, '$source', '$title', '$authors', '$publisher', $howmany, $userid)";
                $result = mysqli_query($link, $query);
                if (!$result) {
                    $success = "insertFailed" . mysqli_error();
                } else {
                    $success = "yes";
                }
                // mysqli_free_result($result);
            } else {
                $row = mysqli_fetch_assoc($result);
                $qty = $row['quantity'];
                $newqty = $qty + $howmany;
                $query = "update inventory set quantity = $newqty where isbn = $isbn";
                $result = mysqli_query($link, $query);
                if (!$result) {
                    $success = "qtyUpdateFailed" . mysqli_error();
                } else {
                    $success = "yes";
                }
                // mysqli_free_result($result);
            }

        } else {
            // Find if the book exists if so then obtain current qty, subtract, then update else insert with 0 qty as it means the book was in physical inventory and can now be added to the database
            $query = "select quantity from inventory where isbn = $isbn";
            $result = mysqli_query($link, $query);
            if (mysqli_num_rows($result) < 1) {
                $query = "insert into inventory (isbn, source, title, authors, publisher, quantity, UserID) VALUES ($isbn, '$source', '$title', '$authors', '$publisher', 0, $userid)";
                $result = mysqli_query($link, $query);
                if (!$result) {
                    $success = "qtyUpdateFailed" . mysql_error();
                } else {
                    $success = "yes";
                }
                // mysqli_free_result($result);
            } else {
                $row = mysqli_fetch_assoc($result);
                $qty = $row['quantity'];
                if ($howmany < $qty) {
                    $newqty = $qty - $howmany;
                } else {
                    $newqty = 0;
                }
                $query = "update inventory set quantity = $newqty where isbn = $isbn";
                $result = mysqli_query($link, $query);
                if (!$result) {
                    $success = "qtyUpdateFailed" . mysql_error();
                } else {
                    $success = "yes";
                }
                // mysqli_free_result($result);
            }
        }
        mysqli_close($link); // Close DB before leaving page

        // Now redirect back to same enter page
        if ($source == "scanned") {
            header("Location: file_input.php?success=$success");
        } else {
            header("Location: cio_enter.php?success=$success");
        }
        
    ob_end_flush();
?>
<!-- HTML below added for debug purposes, normally header redirect should prevent the below from showing, comment out redirect to display -->
<html>
    <body>
        <?php
            echo ($query . "<br /><br />");
            echo ($source . " " . $cio . " " . $howmany . " " . $title);
            echo ("<br /><br />");
            var_dump($_REQUEST);
            echo ("<br /><br />");
            var_dump($_POST);
        ?>
    </body>
</html>