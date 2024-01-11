<?php

    include 'config.php';
    $link = mysqli_connect($host, $dbuser, $dbpass, $dbname);

    $ext_loop = 0;
    echo "<html><head>";
    ?>
    <script>
        function pagego() {
            document.body.innerHTML = "";
            window.location.reload(true);
        }
        function reload() {
            var relvar;
            relvar = setTimeout(pagego, 40000);
            
        };
    </script>
    <?php
    echo "<title>Install Scanner</title></head><body>";
    echo "<h1>Install Scanner</h1>";
    echo "<p>Running Scan Passes</p>";
    
    // ************************************
    // ** START BUILD OF RANDOM DATA SET **
    // ************************************
    while ($ext_loop < 20){
        $disp_count = $ext_loop + 1;
        echo "<hr /><p><strong>Scan Pass# $disp_count</strong></p>";
    $outer_loop_count = 0;
    $inner_loop_count = 0;
    $sort_loop = 1;
    $play_loop = 0;
    $p1 = 0;
    $p2 = 0;
    $p3 = 0;
    $b1 = 0;
    $b2 = 0;
    $b3 = 0;
    $g_loop = 0;
    
    while ($outer_loop_count < 8){
        while ($inner_loop_count < 52){
            $card =  mt_rand(1, 52);
            $card_sql = "select * from one_deck where ID = $card";
            $result = mysqli_query($link, $card_sql);
            $row = mysqli_fetch_assoc ($result);
            $ID = $row['ID'];
            $card = $row['card'];
            $flag = $row['flag'];
            if ($flag == 1) {
                $upd_sql = "update one_deck set flag = 2 where ID = $ID";
                $result = mysqli_query($link, $upd_sql);
                $ins_sql = "insert into sort_stack (ID, card, flag) VALUES ($sort_loop, $card, 1)";
                $result = mysqli_query($link, $ins_sql);
                $sort_loop = $sort_loop + 1;
                $inner_loop_count = $inner_loop_count + 1;
            }
        }
        $inner_loop_count = 0;
        $upd_sql = "update one_deck set flag = 1";
        $result = mysqli_query($link, $upd_sql);
        $outer_loop_count = $outer_loop_count + 1;
        
    }
    while ($play_loop < 416){
        $card =  mt_rand(1, 416);
        $card_sql = "select * from sort_stack where ID = $card";
        $result = mysqli_query($link, $card_sql);
        $row = mysqli_fetch_assoc ($result);
        $ID = $row['ID'];
        $card = $row['card'];
        $flag = $row['flag'];
        if ($flag == 1) {
            $upd_sql = "update sort_stack set flag = 2 where ID = $ID";
            $result = mysqli_query($link, $upd_sql);
            $ins_sql = "insert into play_stack (ID, card) VALUES ($ID, $card)";
            $result = mysqli_query($link, $ins_sql);
            $play_loop = $play_loop + 1;
        }
    }
    echo "<p>JSON Request Tables Loaded</P>";
    
    $p_loop = 0;

    // ****************************
    // ** START WORK OF DATA SET **
    // ****************************
    
    while ($g_loop < 68) {

        // *******************
        // ** ZERO OUT VARS **
        // *******************

        $p1 = 0;
        $p2 = 0;
        $p3 = 0;
        $b1 = 0;
        $b2 = 0;
        $b3 = 0;

        // ********************
        // ** PULL FOR RULES **
        // ********************
        $p_loop = $p_loop + 1;
        $sql = "select * from play_stack where ID = $p_loop";
        $result = mysqli_query ($link, $sql);
        $row = mysqli_fetch_assoc ($result);
        $b1 = $row['card'];
        
        $p_loop = $p_loop + 1;
        $sql = "select * from play_stack where ID = $p_loop";
        $result = mysqli_query ($link, $sql);
        $row = mysqli_fetch_assoc ($result);
        $p1 = $row['card'];

        $p_loop = $p_loop + 1;
        $sql = "select * from play_stack where ID = $p_loop";
        $result = mysqli_query ($link, $sql);
        $row = mysqli_fetch_assoc ($result);
        $b2 = $row['card'];
        
        $p_loop = $p_loop + 1;
        $sql = "select * from play_stack where ID = $p_loop";
        $result = mysqli_query ($link, $sql);
        $row = mysqli_fetch_assoc ($result);
        $p2 = $row['card'];
        
        $b_total = $b1 + $b2;
        $p_total = $p1 + $p2;

        if ($b_total > 9) {
            $b_total = $b_total - 10;
        }
        if ($p_total > 9) {
            $p_total = $p_total - 10;
        }

        
        // *******************************
        // **  CHECK FOR COMPLEX RULES  **
        // *******************************
        $draw = 0;
        
        if ($b_total < 8 && $p_total < 8) {
            if ($p_total < 6) {
                $p_loop = $p_loop + 1;
                $sql = "select * from play_stack where ID = $p_loop";
                $result = mysqli_query ($link, $sql);
                $row = mysqli_fetch_assoc ($result);
                $p3 = $row['card'];
                $p_total = $p_total + $p3;
                if ($p_total > 9) {
                    $p_total = $p_total - 10;
                }
                $draw = 1;
            }
            if ($b_total < 3) {
                $b_loop = $b_loop + 1;
                $sql = "select * from play_stack where ID = $p_loop";
                $result = mysqli_query ($link, $sql);
                $row = mysqli_fetch_assoc ($result);
                $b3 = $row['card'];
                $b_total = $b_total + $b3;
                if ($b_total > 9) {
                    $b_total = $b_total - 10;
                }
            }
            if ($b1 + $b2 == 3 && $draw == 1){
                if ($p3 != 8) {
                    $b_loop = $b_loop + 1;
                    $sql = "select * from play_stack where ID = $p_loop";
                    $result = mysqli_query ($link, $sql);
                    $row = mysqli_fetch_assoc ($result);
                    $b3 = $row['card'];
                    $b_total = $b_total + $b3;
                    if ($b_total > 9) {
                        $b_total = $b_total - 10;
                    }
                }
            }
            if ($b1 + $b2 == 3 && $draw == 0) {
                $b_loop = $b_loop + 1;
                $sql = "select * from play_stack where ID = $p_loop";
                $result = mysqli_query ($link, $sql);
                $row = mysqli_fetch_assoc ($result);
                $b3 = $row['card'];
                $b_total = $b_total + $b3;
                if ($b_total > 9) {
                    $b_total = $b_total - 10;
                }
            }

            if ($b1 + $b2 == 4 && $draw == 1){
                if ($p3 != 1 && $p3 != 8 && $p3 != 9 && $p3 != 0) {
                    $b_loop = $b_loop + 1;
                    $sql = "select * from play_stack where ID = $p_loop";
                    $result = mysqli_query ($link, $sql);
                    $row = mysqli_fetch_assoc ($result);
                    $b3 = $row['card'];
                    $b_total = $b_total + $b3;
                    if ($b_total > 9) {
                        $b_total = $b_total - 10;
                    }
                }
            }
            if ($b1 + $b2 == 4 && $draw == 0) {
                $b_loop = $b_loop + 1;
                $sql = "select * from play_stack where ID = $p_loop";
                $result = mysqli_query ($link, $sql);
                $row = mysqli_fetch_assoc ($result);
                $b3 = $row['card'];
                $b_total = $b_total + $b3;
                if ($b_total > 9) {
                    $b_total = $b_total - 10;
                }
            }

            if ($b1 + $b2 == 5 && $draw == 1){
                if ($p3 != 1 && $p3 != 2 && $p3 != 3 && $p3 != 8 && $p3 != 9 && $p3 != 0) {
                    $b_loop = $b_loop + 1;
                    $sql = "select * from play_stack where ID = $p_loop";
                    $result = mysqli_query ($link, $sql);
                    $row = mysqli_fetch_assoc ($result);
                    $b3 = $row['card'];
                    $b_total = $b_total + $b3;
                    if ($b_total > 9) {
                        $b_total = $b_total - 10;
                    }
                }
            }
            if ($b1 + $b2 == 5 && $draw == 0) {
                $b_loop = $b_loop + 1;
                $sql = "select * from play_stack where ID = $p_loop";
                $result = mysqli_query ($link, $sql);
                $row = mysqli_fetch_assoc ($result);
                $b3 = $row['card'];
                $b_total = $b_total + $b3;
                if ($b_total > 9) {
                    $b_total = $b_total - 10;
                }
            }

            if ($b1 + $b2 == 6 && $draw == 1){
                if ($p3 != 1 && $p3 != 2 && $p3 != 3 && $p3 != 4 && $p3 != 5 && $p3 != 8 && $p3 != 9 && $p3 != 0) {
                    $b_loop = $b_loop + 1;
                    $sql = "select * from play_stack where ID = $p_loop";
                    $result = mysqli_query ($link, $sql);
                    $row = mysqli_fetch_assoc ($result);
                    $b3 = $row['card'];
                    $b_total = $b_total + $b3;
                    if ($b_total > 9) {
                        $b_total = $b_total - 10;
                    }
                }
            }
            if ($b1 + $b2 == 6 && $draw == 0) {
                $b_loop = $b_loop + 1;
                $sql = "select * from play_stack where ID = $p_loop";
                $result = mysqli_query ($link, $sql);
                $row = mysqli_fetch_assoc ($result);
                $b3 = $row['card'];
                $b_total = $b_total + $b3;
                if ($b_total > 9) {
                    $b_total = $b_total - 10;
                } 
            }
        }
        


        // *************************
        // **  LOAD RESULTS TABLE **
        // *************************
    
        if ($b_total > $p_total) {
            $sql = "insert into results (result) VALUES(1)";
        }
        
        if ($p_total > $b_total) {
            $sql = "insert into results (result) VALUES(2)";
        }
        
        if ($b_total == $p_total) {
            $sql = "insert into results (result) VALUES(3)";
        }
        $result = mysqli_query ($link, $sql);
        $g_loop = $g_loop + 1;
    }




    echo "<P>Full Installs (five or more nodes): Las Vegas, San Diego, Reno</P>";

    // **************************
    // ** CLEAR STACKS AND END **
    // **************************

    $sql = "delete from sort_stack where 1";
    $result = mysqli_query ($link, $sql);
    $sql = "delete from play_stack where 1";
    $result = mysqli_query ($link, $sql);

    echo "<p>Partial Installs (One to four nodes): SCC, Austin</P>";

    $ext_loop = $ext_loop + 1;
}


    echo "<hr /><p><h2>PROCESSING COMPLETED</h2></p>";
    echo "</body></html>";
?>