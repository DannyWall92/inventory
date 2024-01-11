<?php
    ob_start();
        if(!isset($_COOKIE["userid"])) {
            header("Location: ../login.php");
        } else {
            $userid = $_COOKIE["userid"];
        }
    ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Entered Check In/Out</title>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="description" content=""/>
    <meta name="viewport" content="width=device-width; initial-scale=1.0"/>
    <link rel="stylesheet" type="text/css" href="css/styles.css"/>
    <script type="text/javascript" src="https://www.google.com/books/jsapi.js"></script>
</head>

<body>
    <script src="vendor/jquery-1.9.0.min.js" type="text/javascript"></script>
    <script>

        function getBookInfo() {
            var code = $("#isbn").val();
            var $node = $('<li><div class="code"></div></li>');
            
            var url = "https://www.googleapis.com/books/v1/volumes?q=isbn:" + code;
            var howMany = $("#inout").val();

            $.getJSON(url, function(data) {
                var totalitems = data["totalItems"];

                if (totalitems == "0") { 
                    tryAgain();
                } else {
                    var book = data.items[0];
                    var title = (book["volumeInfo"]["title"]);
                    var publisher = (book["volumeInfo"]["publisher"]);
                    var authors = (book["volumeInfo"]["authors"]);
                    var thehtml = "hold";
                    $node = $('<li><div class="code"></div></li>');

                    thehtml = "<form action='cio.php' name='cio' id='cio' method='post'>";
                        thehtml = thehtml + "<input type='hidden' id='source' name='source' value='entered'>";
                        thehtml = thehtml + "<input type='hidden' id='howmany' name='howmany' value='" + howMany + "'>";
                        thehtml = thehtml + "<input type='hidden' id='isbn' name='isbn' value='" + code + "'>";
                        thehtml = thehtml + "<input type='hidden' id='title' name='title' value='" + title + "'>";
                        thehtml = thehtml + "<input type='hidden' id='authors' name='authors' value='" + authors + "'>";
                        thehtml = thehtml + "<input type='hidden' id='publisher' name='publisher' value='" + publisher + "'>";
                        thehtml = thehtml + "Checking In: " + howMany + " of ISBN: " + code + "<br />Title: " + title + "<br />Authors: " + authors + "<br />Publisher: " + publisher + "<br />";
                        thehtml = thehtml + "For? <select id='cio' name='cio'>";
                            thehtml = thehtml + "<option value='checkin'>Check In</otion>";
                            thehtml = thehtml + "<option value='checkout'>Check Out</otion><br />";
                        thehtml = thehtml + "</select><br /><br />";
                        thehtml = thehtml + "<input type='submit' name='submit' value='Submit'>";
                    thehtml = thehtml + "</form>";
                }

                $node.find("div.code").html(thehtml);
            
                $("#result_strip ul.thumbnails").prepend($node);
            });
        }

        function getUrlParameter(sParam) {
            var sPageURL = window.location.search.substring(1),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');

                if (sParameterName[0] === sParam) {
                    return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
                }
            }
        }

        function tryAgain() {
            var code = $("#isbn").val();
            var url = "json.php?isbn=" + code;
            var $node = $('<li><div class="code"></div></li>');
            var howMany = $("#inout").val();
            $.getJSON(url, function(invdata) {
                var totalitems = invdata["totalItems"];
                if (totalitems == "0") {
                    var thehtml = "<h2>Item Not Found In Online Search.  Manual Entry Required.</h2>";
                    thehtml = thehtml + "<form action='cio.php' name='cio' id='cio' method='post'>";
                        thehtml = thehtml + "<input type='hidden' id='source' name='source' value='entered'>";
                        thehtml = thehtml + "<input type='hidden' id='howmany' name='howmany' value='" + howMany + "'>";
                        thehtml = thehtml + "ISBN: <input type='text' id='isbn' name='isbn' value='" + code + "'><br />";
                        thehtml = thehtml + "Title: <input type='text' id='title' name='title'><br />";
                        thehtml = thehtml + "Authors: <input type='text' id='authors' name='authors'><br />";
                        thehtml = thehtml + "Publisher: <input type='text' id='publisher' name='publisher'><br />";
                        thehtml = thehtml + "For? <select id='cio' name='cio'>";
                            thehtml = thehtml + "<option value='checkin'>Check In</otion>";
                            thehtml = thehtml + "<option value='checkout'>Check Out</otion><br />";
                        thehtml = thehtml + "</select><br /><br />";
                        thehtml = thehtml + "<input type='submit' name='submit' value='Submit'>";
                    thehtml = thehtml + "</form>";
                    $node.find(".code").html(thehtml);
                    $("#result_strip ul.thumbnails").prepend($node);
                } else {
                    var book = invdata.items[0];
                    var title = (book["volumeInfo"]["title"]);
                    var publisher = (book["volumeInfo"]["publisher"]);
                    var authors = (book["volumeInfo"]["authors"]);
                    var thehtml = "<form action='cio.php' name='cio' id='cio' method='post'>";
                        thehtml = thehtml + "<input type='hidden' id='source' name='source' value='entered'>";
                        thehtml = thehtml + "<input type='hidden' id='howmany' name='howmany' value='" + howMany + "'>";
                        thehtml = thehtml + "<input type='hidden' id='isbn' name='isbn' value='" + code + "'>";
                        thehtml = thehtml + "<input type='hidden' id='title' name='title' value='" + title + "'>";
                        thehtml = thehtml + "<input type='hidden' id='authors' name='authors' value='" + authors + "'>";
                        thehtml = thehtml + "<input type='hidden' id='publisher' name='publisher' value='" + publisher + "'>";
                        thehtml = thehtml + "Checking In: " + howMany + " of ISBN: " + code + "<br />Title: " + title + "<br />Authors: " + authors + "<br />Publisher: " + publisher + "<br />";
                        thehtml = thehtml + "For? <select id='cio' name='cio'>";
                            thehtml = thehtml + "<option value='checkin'>Check In</otion>";
                            thehtml = thehtml + "<option value='checkout'>Check Out</otion><br />";
                        thehtml = thehtml + "</select><br /><br />";
                        thehtml = thehtml + "<input type='submit' name='submit' value='Submit'>";
                    thehtml = thehtml + "</form>";
                    $node.find(".code").html(thehtml);
                    $("#result_strip ul.thumbnails").prepend($node);
                }
            }); 
        }

    </script>

    <header>
        <div class="headline">
            <h1 id="headline" style="text-align: center;">Entered Check In/Out</h1>            
            <a href="../index.php" style="color: yellow;">Home Screen</a>
        </div>
    </header>

    <section id="container" class="container">
        <div class="controls">
            <fieldset class="reader-config-group">
                <?php
                if (isset ($_GET['isbn'])) {
                    $isbn = $_GET['isbn'];
                    echo ("<label><span>ISBN:</span><input type='text' id='isbn' name='isbn' value='$isbn'/></label>");
                } else {
                    echo ("<label><span>ISBN:</span><input type='text' id='isbn' name='isbn' /></label>");
                }
                ?>
                <label><span>How Many?</span><input type="text" id="inout" name="inout" /></label>
                <label></label><button onclick="getBookInfo()">Find It</button>
            </fieldset>
        </div>
        <div id="result_strip">
            <ul class="thumbnails"></ul>
        </div>
        <div id="interactive" class="viewport"></div>
        <div id="debug" class="detection"></div>
    </section>
    <footer>
        <p>
            &copy; Cassie Marquez.  For support contact <a href="mailto:dehe2792@gmail.com" style="color: yellow;">Danny Wall</a> by email.
        </p>
    </footer>
    <script>
        var success = getUrlParameter('success');
        if (success == "yes") {
            alert("Entry Successful");
        } else {
            if (!success){
                $do = "nothing";
            } else {
                alert(success);
            }
        }
    </script>
</body>
</html>
