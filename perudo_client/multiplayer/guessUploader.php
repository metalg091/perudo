<?php
session_start();
?>
<html>
<head>
    <link rel="stylesheet" href="../dark_theme.css">
    <script src="../themeSwitch.js"></script>
</head>
<body>
<?php
        $conn = mysqli_connect("localhost", "root", "", "perudo");
        if (!$conn){
            die("Connection failed: " . mysqli_connect_error());
        }
        $sql = "SELECT orders FROM eventtable ORDER BY orders DESC LIMIT 1";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_assoc($result);
            $neworderid = $row["orders"] + 1;
        }
        else{
            $neworderid = 0;
        }
        switch($_POST["iguess"]){
            case 3:
                $guess = intval($_POST["guess1"] * 10 + $_POST["guess2"]);
                echo $guess . "<br>";
                guess($conn, $guess);
                $sql = "INSERT INTO eventtable (orders, ide, guess) VALUES (" . $neworderid . ", " . $_SESSION["id"] . ", " . $guess . ")";
                break;
            case 1:
                $sql = "INSERT INTO eventtable (orders, ide, guess) VALUES (" . $neworderid . ", " . $_SESSION["id"] . ", '''doubt''')";
                doubt($conn, $_SESSION["id"], $neworderid);
                roll($conn);
                break;
            case 2:
                $equal = "equal";
                $sql = "INSERT INTO eventtable (orders, ide, guess) VALUES (" . $neworderid . ", " . $_SESSION["id"] . ", '''" . $equal . "''')";
                equal($conn, $_SESSION["id"], $neworderid);
                roll($conn);
                break;
        }
        if(mysqli_query($conn, $sql)){
            echo "succes <br>";
            $sql = "UPDATE `game` SET `cycle`= 0 WHERE id = 0";
            if(mysqli_query($conn, $sql)){
                echo "succes again <br>";
                header('Location: waitingForTurn.php');
            }
            else{
                echo "Error updating record: " . mysqli_error($conn);
            }
        }
        else{
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            echo "I will try again in 15 seconds!";
        }

        function roll($conn){
            
            $sql = "SELECT id, cubes FROM game";
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) > 0){
                $id = Array();
                $cubes = Array();
                while($row = mysqli_fetch_assoc($result)){
                    $id[] = $row["id"];
                    $cubes[] = $row["cubes"];
                }
            }
            unset($row);
            $key = array_search(0, $cubes);
            while(is_int($key)){
                unset($id[$key]);
                unset($cubes[$key]);
                $key = array_search(0, $cubes);
            }
            $cubes = array_values(array_filter($cubes));
            $id = array_values(array_filter($id));

            $num = Array();
            $numstr = "";
            for($y = 0; count($id)> $y; $y++){
                for ($x = 0; $cubes[$y]>$x; $x++){
                    $num[] = random_int(1, 6);
                    $numstr = $numstr . $num[$x];
                }
                $sql = "UPDATE game SET numbers = '" .  $numstr . "' WHERE id = '" . $id[$y] . "'";
                if(mysqli_query($conn, $sql)){
                    echo "roll success";
                    unset($num);
                    $numstr = null;
                }
                else{
                    echo "error";
                }
            }
        }
        function geteach($numbers, $counts){
            $eachnum = str_split($numbers);
            foreach ($eachnum as $anum) {
                switch($anum){
                    case 1:
                        $counts[0]++;
                        break;
                    case 2:
                        $counts[1]++;
                        break;
                    case 3:
                        $counts[2]++;
                        break;
                    case 4:
                        $counts[3]++;
                        break;
                    case 5:
                        $counts[4]++;
                        break;
                    case 6:
                        $counts[5]++;
                        break;
                }
            }
            return $counts;
        }
        function doubt($conn, $sesid, $neworderid){
            $sql = "SELECT guess FROM eventtable ORDER BY orders DESC LIMIT 2";
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) > 0){
                $guess = Array();
                while($row = mysqli_fetch_assoc($result)){
                    $guess[] = $row["guess"];
                }
            }
            else{
                echo "error";
            }
            $guesstr = $guess[0];
            unset($guess);

            $sql = "SELECT id, cubes, numbers FROM game";
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) > 0){
                $id = Array();
                $cubes = Array();
                $numbers = Array();
                while($row = mysqli_fetch_assoc($result)){
                    $id[] = $row["id"];
                    $cubes[] = $row["cubes"];
                    $numbers[] = $row["numbers"];
                }
            }
            unset($row);
            $key = array_search(0, $cubes);
            while(is_int($key)){
                unset($id[$key]);
                unset($cubes[$key]);
                unset($numbers[$key]);
                $key = array_search(0, $cubes);
            }
            $cubes = array_values(array_filter($cubes));
            $numbers = array_values(array_filter($numbers));
            $counts = Array(0, 0, 0, 0, 0, 0);
            foreach($numbers as $var){
                $counts = geteach($var, $counts);
            }
            $id = array_values(array_filter($id));
            $val= array_search($sesid, $id);
            $newcube = $cubes[$val] - 1;
            switch (substr($guesstr, -1))
            {
                case 1:
                    if ($counts[0] * 10 + 1 >= $guesstr)
                    {
                        $sql = "UPDATE game SET cubes = " . $newcube . " WHERE id = " . $_SESSION["id"];
                    }
                    else
                    {
                        $val--;
                        $newcube = $cubes[$val] - 1;
                        $sql = "UPDATE game SET cubes = " . $newcube . " WHERE id = " . $id[$val];
                    }
                    break;
                case 2:
                    if ($counts[1] * 10 + 2 + $counts[0] * 10 >= $guesstr)
                    {
                        $sql = "UPDATE game SET cubes = " . $newcube . " WHERE id = " . $_SESSION["id"];
                    }
                    else
                    {
                        $val--;
                        $newcube = $cubes[$val] - 1;
                        $sql = "UPDATE game SET cubes = " . $newcube . " WHERE id = " . $id[$val];
                    }
                    break;
                case 3:
                    if ($counts[2] * 10 + 3 + $counts[0] * 10 >= $guesstr)
                    {
                        $sql = "UPDATE game SET cubes = " . $newcube . " WHERE id = " . $_SESSION["id"];
                    }
                    else
                    {
                        $val--;
                        $newcube = $cubes[$val] - 1;
                        $sql = "UPDATE game SET cubes = " . $newcube . " WHERE id = " . $id[$val];
                    }
                    break;
                case 4:
                    if ($counts[3] * 10 + 4 + $counts[0] * 10 >= $guesstr)
                    {
                        $sql = "UPDATE game SET cubes = " . $newcube . " WHERE id = " . $_SESSION["id"];
                    }
                    else
                    {
                        $val--;
                        $newcube = $cubes[$val] - 1;
                        $sql = "UPDATE game SET cubes = " . $newcube . " WHERE id = " . $id[$val];
                    }
                    break;
                case 5:
                    if ($counts[4] * 10 + 5 + $counts[0] * 10 >= $guesstr)
                    {
                        $sql = "UPDATE game SET cubes = " . $newcube . " WHERE id = " . $_SESSION["id"];
                    }
                    else
                    {
                        $val--;
                        $newcube = $cubes[$val] - 1;

                        $sql = "UPDATE game SET cubes = " . $newcube . " WHERE id = " . $id[$val];
                    }
                    break;
                case 6:
                    if ($counts[5] * 10 + 6 + $counts[0] * 10 >= $guesstr)
                    {
                        $sql = "UPDATE game SET cubes = " . $newcube . " WHERE id = " . $_SESSION["id"];
                    }
                    else
                    {
                        $val--;
                        $newcube = $cubes[$val] - 1;
                        $sql = "UPDATE game SET cubes = " . $newcube . " WHERE id = " . $id[$val];
                    }
                    break;
            }
            if(mysqli_query($conn, $sql)){
                $neworderid++;
                if($newcube == 0){
                    $sql = "INSERT (eventtable orders, ide, guess, who) VALUES (" . $neworderid . ", 0, -2, " . $id[$val] . ")";
                }
                else{
                    $sql = "INSERT (eventtable orders, ide, guess, who) VALUES (" . $neworderid . ", 0, -1, " . $id[$val] . ")";
                }
                if(mysqli_query($conn, $sql)){
                    echo "doubt is done";
                }
                else{
                    echo "Error in doubt:" . mysqli_error($conn);
                }
            }
            else{
                echo "Error doubting:" . mysqli_error($conn);
            }
        }
        function equal($conn, $sesid, $neworderid){
            $sql = "SELECT guess FROM eventtable ORDER BY orders DESC LIMIT 2";
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) > 0){
                $guess = Array();
                while($row = mysqli_fetch_assoc($result)){
                    $guess[] = $row["guess"];
                }
            }
            else{
                echo "error";
            }
            $guesstr = $guess[0];
            unset($guess);

            $sql = "SELECT id, cubes, numbers FROM game";
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) > 0){
                $id = Array();
                $cubes = Array();
                $numbers = Array();
                while($row = mysqli_fetch_assoc($result)){
                    $id[] = $row["id"];
                    $cubes[] = $row["cubes"];
                    $numbers[] = $row["numbers"];
                }
            }
            unset($row);
            $key = array_search(0, $cubes);
            while(is_int($key)){
                unset($id[$key]);
                unset($cubes[$key]);
                unset($numbers[$key]);
                $key = array_search(0, $cubes);
            }
            $cubes = array_values(array_filter($cubes));
            $numbers = array_values(array_filter($numbers));
            $counts = Array(0, 0, 0, 0, 0, 0);
            foreach($numbers as $var){
                $counts = geteach($var, $counts);
            }
            //echo json_encode($counts);
            $id = array_values(array_filter($id));
            $val= array_search($sesid, $id);
            $cube = $cubes[$val];
            switch (substr($guesstr, -1))
            {
                case 1:
                    if ($counts[0] * 10 + 1 == $guesstr && $cube < 5)
                    {
                        $cube++;
                        echo "increased";
                        $sql = "UPDATE game SET cubes = " . $cube . " WHERE id = " . $_SESSION["id"];
                    }
                    elseif ($counts[0] * 10 + 1 == $guesstr && !$cubes < 5){
                        echo "max cubes";
                        $sql = "";
                        break;
                    }
                    else
                    {
                        $cube--;
                        echo "decreased";
                        $sql = "UPDATE game SET cubes = " . $cube . " WHERE id = " . $_SESSION["id"];
                    }
                    break;
                case 2:
                    if ($counts[1] * 10 + 2 + $counts[0] * 10 == $guesstr && $cube < 5)
                    {
                        $cube++;
                        echo "increased";
                        $sql = "UPDATE game SET cubes = " . $cube . " WHERE id = " . $_SESSION["id"];
                    }
                    elseif ($counts[1] * 10 + 1 + $counts[0] * 10 == $guesstr && !$cubes < 5){
                        echo "max cubes";
                        $sql = "";
                        break;
                    }
                    else
                    {
                        $cube--;
                        echo "decreased";
                        $sql = "UPDATE game SET cubes = " . $cube . " WHERE id = " . $_SESSION["id"];
                    }
                    break;
                case 3:
                    if ($counts[2] * 10 + 3 + $counts[0] * 10 == $guesstr && $cube < 5)
                    {
                        $cube++;
                        echo "increased";
                        $sql = "UPDATE game SET cubes = " . $cube . " WHERE id = " . $_SESSION["id"];
                    }
                    elseif ($counts[2] * 10 + 1 + $counts[0] * 10 == $guesstr && !$cubes < 5){
                        echo "max cubes";
                        $sql = "";
                        break;
                    }
                    else
                    {
                        $cube--;
                        echo "decreased";
                        $sql = "UPDATE game SET cubes = " . $cube . " WHERE id = " . $_SESSION["id"];
                    }
                    break;
                case 4:
                    if ($counts[3] * 10 + 4 + $counts[0] * 10 == $guesstr && $cube < 5)
                    {
                        $cube++;
                        echo "increased";
                        $sql = "UPDATE game SET cubes = " . $cube . " WHERE id = " . $_SESSION["id"];
                    }
                    elseif ($counts[3] * 10 + 1 + $counts[0] * 10 == $guesstr && !$cubes < 5){
                        echo "max cubes";
                        $sql = "";
                        break;
                    }
                    else
                    {
                        $cube--;
                        echo "decreased";
                        $sql = "UPDATE game SET cubes = " . $cube . " WHERE id = " . $_SESSION["id"];
                    }
                    break;
                case 5:
                    if ($counts[4] * 10 + 5 + $counts[0] * 10 == $guesstr && $cube < 5)
                    {
                        $cube++;
                        echo "increased";
                        $sql = "UPDATE game SET cubes = " . $cube . " WHERE id = " . $_SESSION["id"];
                    }
                    elseif ($counts[4] * 10 + 1 + $counts[0] * 10 == $guesstr && !$cubes < 5){
                        echo "max cubes";
                        $sql = "";
                        break;
                    }
                    else
                    {
                        $cube--;
                        echo "decreased";
                        $sql = "UPDATE game SET cubes = " . $cube . " WHERE id = " . $_SESSION["id"];
                    }
                    break;
                case 6:
                    if ($counts[5] * 10 + 6 + $counts[0] * 10 == $guesstr && $cube < 5)
                    {
                        $cube++;
                        echo "increased";
                        $sql = "UPDATE game SET cubes = " . $cube . " WHERE id = " . $_SESSION["id"];
                    }
                    elseif ($counts[5] * 10 + 1 + $counts[0] * 10 == $guesstr && !$cubes < 5){
                        echo "max cubes";
                        $sql = "";
                        break;
                    }
                    else
                    {
                        $cube--;
                        echo "decreased";
                        $sql = "UPDATE game SET cubes = " . $cube . " WHERE id = " . $_SESSION["id"];
                    }
                    break;
            }
            $neworderid++;
            if(sql == ""){
                if(mysqli_query($conn, $sql)){
                    if($cube < $cubes[$val]){
                        if($cube == 0){
                            $sql = "INSERT (eventtable orders, ide, guess, who) VALUES (" . $neworderid . ", 0, -2, " . $id[$val] . ")";
                        }
                        else{
                            $sql = "INSERT (eventtable orders, ide, guess, who) VALUES (" . $neworderid . ", 0, -1, " . $id[$val] . ")";
                        }
                    }
                    else{
                        $sql = "INSERT (eventtable orders, ide, guess, who) VALUES (" . $neworderid . ", 0, 1, " . $id[$val] . ")";
                    }
                }
                else{
                    echo "Error in equal:" . mysqli_error($conn);
                }
                if(mysqli_query($conn, $sql)){
                    echo "equal is done";
                }
                else{
                    echo "Error in equal:" . mysqli_error($conn);
                }
            }
        }
        function guess($conn, $newguess){
            $sql = "SELECT guess FROM eventtable ORDER BY orders DESC LIMIT 1";
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) > 0){
                $guess = Array();
                while($row = mysqli_fetch_assoc($result)){
                    $guess[] = $row["guess"];
                }
            }
            else{
                echo "error";
            }
            $guess = $guess[0];
            if(substr(strval($newguess), -1) == 0 || substr(strval($newguess), -1) == 7 || substr(strval($newguess), -1) == 8 || substr(strval($newguess), -1) == 9){
                header('Location: guessTurn.php');
                die("invalid input");
            }
            if(substr(strval($newguess), -1) == 1){
                $a = 2;
            }
            else{
                $a = 1;
            }
            if(substr(strval($guess), -1) == 1){
                $b = 2;
            }
            else{
                $b = 1;
            }
            $ng = $newguess/$b;
            $lg = $guess/$a;
            if($ng > $lg){
                echo "nothing";
            }
            else{
                header('Location: guessTurn.php');
                die("too small input");
            }
        }
        mysqli_close($conn);
    ?>
    <p id="a"></p>
    <script defer type="text/javascript">
        /*var reload = '<?php //echo $succes; ?>'; //php runner, redirecter
        document.getElementById("a").innerHTML = reload;
        switch(reload){
            case 1:
                location.href = 'waitingForTurn.php';
                break;
            case 2:
                location.href = 'guessTurn.php';
                break;
        }*/
    </script>
</body>
</html>