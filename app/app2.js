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

function tryAgain(code) {
    var url = "json.php?isbn=" + code;
    var $node = $('<li><div class="code"></div></li>');
    var howMany = $("#inout").val();
    $.getJSON(url, function(invdata) {
        // alert("inside second try of getJSON");
        var totalitems = invdata["totalItems"];
        if (totalitems == "0") {
            var thehtml = "<h2>Item Not Found In Online Search.  Manual Entry Required.</h2>";
            thehtml = thehtml + "<form action='cio.php' name='cio' id='cio' method='post'>";
                thehtml = thehtml + "<input type='hidden' id='source' name='source' value='scanned'>";
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
                thehtml = thehtml + "<input type='hidden' id='source' name='source' value='scanned'>";
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

$(function() {
    var App = {
        init: function() {
            App.attachListeners();
        },
        attachListeners: function() {
            var self = this;

            $(".controls input[type=file]").on("change", function(e) {
                if (e.target.files && e.target.files.length) {
                    App.decode(URL.createObjectURL(e.target.files[0]));
                }
            });

            $(".controls button").on("click", function(e) {
                var input = document.querySelector(".controls input[type=file]");
                if (input.files && input.files.length) {
                    App.decode(URL.createObjectURL(input.files[0]));
                }
            });

            $(".controls .reader-config-group").on("change", "input, select", function(e) {
                e.preventDefault();
                var $target = $(e.target),
                    value = $target.attr("type") === "checkbox" ? $target.prop("checked") : $target.val(),
                    name = $target.attr("name"),
                    state = self._convertNameToState(name);

                console.log("Value of "+ state + " changed to " + value);
                self.setState(state, value);
            });
        },
        _accessByPath: function(obj, path, val) {
            var parts = path.split('.'),
                depth = parts.length,
                setter = (typeof val !== "undefined") ? true : false;

            return parts.reduce(function(o, key, i) {
                if (setter && (i + 1) === depth) {
                    o[key] = val;
                }
                return key in o ? o[key] : {};
            }, obj);
        },
        _convertNameToState: function(name) {
            return name.replace("_", ".").split("-").reduce(function(result, value) {
                return result + value.charAt(0).toUpperCase() + value.substring(1);
            });
        },
        detachListeners: function() {
            $(".controls input[type=file]").off("change");
            $(".controls .reader-config-group").off("change", "input, select");
            $(".controls button").off("click");
        },
        decode: function(src) {
            var self = this,
                config = $.extend({}, self.state, {src: src});

            Quagga.decodeSingle(config, function(result) {});
        },
        setState: function(path, value) {
            var self = this;

            if (typeof self._accessByPath(self.inputMapper, path) === "function") {
                value = self._accessByPath(self.inputMapper, path)(value);
            }

            self._accessByPath(self.state, path, value);

            console.log(JSON.stringify(self.state));
            App.detachListeners();
            App.init();
        },
        inputMapper: {
            inputStream: {
                size: function(value){
                    return parseInt(value);
                }
            },
            numOfWorkers: function(value) {
                return parseInt(value);
            },
            decoder: {
                readers: function(value) {
                    if (value === 'ean_extended') {
                        return [{
                            format: "ean_reader",
                            config: {
                                supplements: [
                                    'ean_5_reader', 'ean_2_reader'
                                ]
                            }
                        }];
                    }
                    return [{
                        format: value + "_reader",
                        config: {}
                    }];
                }
            }
        },
        state: {
            inputStream: {
                size: 800,
                singleChannel: false
            },
            locator: {
                patchSize: "medium",
                halfSample: true
            },
            decoder: {
                readers: [{
                    format: "code_128_reader",
                    config: {}
                }]
            },
            locate: true,
            src: null
        }
    };

    App.init();
    

    function calculateRectFromArea(canvas, area) {
        var canvasWidth = canvas.width,
            canvasHeight = canvas.height,
            top = parseInt(area.top)/100,
            right = parseInt(area.right)/100,
            bottom = parseInt(area.bottom)/100,
            left = parseInt(area.left)/100;

        top *= canvasHeight;
        right = canvasWidth - canvasWidth*right;
        bottom = canvasHeight - canvasHeight*bottom;
        left *= canvasWidth;

        return {
            x: left,
            y: top,
            width: right - left,
            height: bottom - top
        };
    }

    Quagga.onProcessed(function(result) {
        var drawingCtx = Quagga.canvas.ctx.overlay,
            drawingCanvas = Quagga.canvas.dom.overlay,
            area;

        if (result) {
            if (result.boxes) {
                drawingCtx.clearRect(0, 0, parseInt(drawingCanvas.getAttribute("width")), parseInt(drawingCanvas.getAttribute("height")));
                result.boxes.filter(function (box) {
                    return box !== result.box;
                }).forEach(function (box) {
                    Quagga.ImageDebug.drawPath(box, {x: 0, y: 1}, drawingCtx, {color: "green", lineWidth: 2});
                });
            }

            if (result.box) {
                Quagga.ImageDebug.drawPath(result.box, {x: 0, y: 1}, drawingCtx, {color: "#00F", lineWidth: 2});
            }

            if (result.codeResult && result.codeResult.code) {
                Quagga.ImageDebug.drawPath(result.line, {x: 'x', y: 'y'}, drawingCtx, {color: 'red', lineWidth: 3});
            }

            if (App.state.inputStream.area) {
                area = calculateRectFromArea(drawingCanvas, App.state.inputStream.area);
                drawingCtx.strokeStyle = "#0F0";
                drawingCtx.strokeRect(area.x, area.y, area.width, area.height);
            }
    }
    });

    Quagga.onDetected(function(result) {
        var code = result.codeResult.code;
        var $node = $('<li><div class="code"></div></li>');
    
        var url = "https://www.googleapis.com/books/v1/volumes?q=isbn:" + code;
        var howMany = $("#inout").val();
        
        $.getJSON(url, function(data) {
            var totalitems = data["totalItems"];
            // alert ("first go total items: " + totalitems);
            // alert (code);
            
            if (totalitems == "0") {
                tryAgain(code);
            } else { /*
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
            */ }

            $node.find("div.code").html(thehtml);
        
            $("#result_strip ul.thumbnails").prepend($node);
            
        });
    });
});
