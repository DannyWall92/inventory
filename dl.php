<?php //Generate text file on the fly
        if(!isset($_COOKIE["userid"])) {
            header("Location: ../login.php");
        } else {
            $userid = $_COOKIE["userid"];
        }

    header("Content-type: text/plain");
    header("Content-Disposition: attachment; filename=inventory.tsv");

    echo("ISBN\tTitle\tAuthors\tPublisher\tQuantity\n");
    $link = mysqli_connect('localhost', 'dew_app', '1!Spider!1', 'dew_inventoryapp');
    $query = "select * from inventory where UserID = $userid";
    $result = mysqli_query ($link, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $isbn = $row['isbn'];
        $title = $row['title'];
        $authors = $row['authors'];
        $publisher = $row['publisher'];
        $quantity = $row['quantity'];
        echo ("$isbn\t$title\t$authors\t$publisher\t$quantity\n");
    }
?>