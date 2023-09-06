<?php
    if(!isset($_COOKIE["userid"])) {
        $display="no";
    } else {
        $display = "yes";
    }
    $isbn = $_GET['isbn'];
    $link = mysqli_connect('localhost', 'root', 'mysql', 'inventoryapp');
    $query = "select * from inventory where isbn = $isbn";
    $result = mysqli_query($link, $query);
    if (mysqli_num_rows($result) ==1 && $display == "yes"){
        $row = mysqli_fetch_assoc($result);
        $title = $row['title'];
        $authors = $row['authors'];
        $publisher = $row['publisher'];
        header('Content-Type: application/json');
        $thejson = "{\n";
        $thejson .= "\"totalItems\": 1,\n";
        $thejson .= "\"items\": [\n";
        $thejson .= "{\n";
        $thejson .= "\"volumeInfo\": {\n";
        $thejson .= "\"title\": \"$title\",\n";
        $thejson .= "\"authors\": [\n";
        $thejson .= "\"$authors\"\n";
        $thejson .= "],\n";
        $thejson .= "\"publisher\": \"$publisher\"\n";
        $thejson .= "}\n";
        $thejson .= "}\n";
        $thejson .= "]\n";
        $thejson .= "}\n";
        echo ($thejson);
    } else {
        header('Content-Type: application/json');
        echo ("{\n\"kind\": \"books#volumes\",\n\"totalItems\": 0\n}");
    }
?>