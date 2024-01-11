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
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>

    <title>Book Scan</title>
    <meta name="description" content=""/>


    <meta name="viewport" content="width=device-width; initial-scale=1.0"/>
    <link rel="stylesheet" type="text/css" href="css/styles.css"/>
    <script type="text/javascript" src="https://www.google.com/books/jsapi.js"></script>
</head>

<body>


    <header>
        <div class="headline">
            <h1 style="text-align: center;">Scanned Check In/Out</h1>
            <a href="../index.php" style="color: yellow;">Home Screen</a>
        </div>
    </header>

    <section id="container" class="container">
        <p>After you specify how many is being checked in/out and after you have changed the barcode type to EAN tap "choose file" to bring up the camera to scan the barcode</p>
        <div class="controls">
            <fieldset class="input-group">
                <input type="file" accept="image/*" capture="camera"/>
                <button>Rerun</button>
            </fieldset>
            <fieldset class="reader-config-group" >
                <label>
                    <span>How Many?</span>
                    <input type="text" id="inout" name="inout" />
                </label>
                <label>
                    <span>Barcode-Type</span>
                    <select name="decoder_readers">
                        <option value="code_128" selected="selected">Select Barcode Type</option>
                        <option value="ean">EAN - Usually this one</option>

                        <option value="code_39">Code 39</option>
                        <option value="code_39_vin">Code 39 VIN</option>
                        
                        <option value="ean_extended">EAN-extended</option>
                        <option value="ean_8">EAN-8</option>
                        <option value="upc">UPC</option>
                        <option value="upc_e">UPC-E</option>
                        <option value="codabar">Codabar</option>
                        <option value="i2of5">Interleaved 2 of 5</option>
                        <option value="2of5">Standard 2 of 5</option>
                        <option value="code_93">Code 93</option>
                    </select>
                </label>
                <label>
                    <span style="display: none;">Resolution (long side)</span>
                    <select name="input-stream_size" style="display: none;">
                        <option value="320">320px</option>
                        <option value="640">640px</option>
                        <option value="800">800px</option>
                        <option selected="selected" value="1280">1280px</option>
                        <option value="1600">1600px</option>
                        <option value="1920">1920px</option>
                    </select>
                </label>
                <label>
                    <span style="display: none;">Patch-Size</span>
                    <select name="locator_patch-size" style="display: none;">
                        <option value="x-small">x-small</option>
                        <option value="small">small</option>
                        <option value="medium">medium</option>
                        <option value="large">large</option>
                        <option selected="selected" value="x-large">x-large</option>
                    </select>
                </label>
                <label>
                    <span style="display: none;">Half-Sample</span>
                    <input type="checkbox" name="locator_half-sample" style="display: none;" />
                </label>
                <label> 
                    <span style="display: none;">Single Channel</span>
                    <input type="checkbox" name="input-stream_single-channel" style="display: none;" />
                </label>
                <label>
                    <span style="display: none;">Workers</span>
                    <select name="numOfWorkers" style="display: none;">
                        <option value="0">0</option>
                        <option selected="selected" value="1">1</option>
                    </select>
                </label>
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
    <script src="vendor/jquery-1.9.0.min.js" type="text/javascript"></script>
    <script src="dist/quagga.js" type="text/javascript"></script>
    <script src="app2.js" type="text/javascript"></script>
    <script>
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
        
        var success = getUrlParameter('success');
        if (success == "yes") {
            alert("Entry Successful");
        } else {
            if (!success){
                var $do = "nothing";
            } else {
                alert(success);
            }
        }
    </script>
</body>
</html>
