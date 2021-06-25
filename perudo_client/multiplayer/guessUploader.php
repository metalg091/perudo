<?php
session_start();
?>
<html>
<head>
    <link rel="stylesheet" href="../main_theme.css">
    <script src="../themeSwitch.js"></script>
</head>
<body>
    <?php
        $db = new SQLite3('../databases/perudo.sqlite', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
        isover($db);
        switch($_POST["iguess"]){
            case 3:
                $guess = intval($_POST["guess1"] * 10 + $_POST["guess2"]);
                echo $guess . "<br>";
                guess($db, $guess);
                $db->exec('BEGIN');
                $db->query('INSERT INTO eventtable (ide, guess) VALUES (' . $_SESSION["id"] . ', ' . $guess . ')');
                $db->exec('COMMIT');
                break;
            case 1:
                $db->exec('BEGIN');
                $db->query('INSERT INTO eventtable (ide, guess) VALUES (' . $_SESSION["id"] . ', "doubt")');
                $db->exec('COMMIT');
                doubt($db, $_SESSION["id"]);
                roll($db);
                break;
            case 2:
                $db->exec('BEGIN');
                $db->query('INSERT INTO eventtable (ide, guess) VALUES (' . $_SESSION["id"] . ', "equal")');
                $db->exec('COMMIT');
                equal($db, $_SESSION["id"]);
                roll($db);
                break;
        }
            //$sql = 'UPDATE `game` SET `cycle`= 0 WHERE id = 0';
        header('Location: waitingForTurn.php');

        function roll($db){
            $result = $db->query('SELECT id, cubes FROM game');
            $id = Array();
            $cubes = Array();
            while($row = $result->fetchArray()){
                $id[] = $row["id"];
                $cubes[] = $row["cubes"];
            }
            $result->finalize();
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
                $db->exec('BEGIN');
                $db->query('UPDATE game SET numbers = ' .  $numstr . ' WHERE id = ' . $id[$y]);
                $db->exec('COMMIT');
                $numstr = null;
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
        function doubt($db, $sesid){
            $guesstr = $db->querySingle('SELECT guess FROM eventtable ORDER BY orders DESC LIMIT 1, 1');
            $result = $db->query('SELECT id, cubes, numbers FROM game');
            $id = Array();
            $cubes = Array();
            $numbers = Array();
            while($row = $result->fetchArray()){
                $id[] = $row["id"];
                $cubes[] = $row["cubes"];
                $numbers[] = $row["numbers"];
            }
            $result->finalize();
            unset($row);
            $key = array_search(0, $cubes);
            while(is_int($key)){
                unset($id[$key]);
                unset($cubes[$key]);
                unset($numbers[$key]);
                $key = array_search(0, $cubes);
            }
            $cubes = array_values(array_filter($cubes));
            $id = array_values(array_filter($id));
            $numbers = array_values(array_filter($numbers));
            $counts = Array(0, 0, 0, 0, 0, 0);
            foreach($numbers as $var){
                $counts = geteach($var, $counts);
            }
            $id = array_values(array_filter($id));
            $val = array_search($sesid, $id);
            switch (substr($guesstr, -1))
            {
                case 1:
                    if (!($counts[0] * 10 + 1 >= $guesstr))
                    {
                        if($val == 0){
                            $val = count($id) - 1;
                        }
                        else{
                            $val--;
                        }
                    }
                    break;
                case 2:
                    if (!($counts[1] * 10 + 2 + $counts[0] * 10 >= $guesstr))
                    {
                        if($val == 0){
                            $val = count($id) - 1;
                        }
                        else{
                            $val--;
                        }
                    }
                    break;
                case 3:
                    if (!($counts[2] * 10 + 3 + $counts[0] * 10 >= $guesstr))
                    {
                        if($val == 0){
                            $val = count($id) - 1;
                        }
                        else{
                            $val--;
                        }
                    }
                    break;
                case 4:
                    if (!($counts[3] * 10 + 4 + $counts[0] * 10 >= $guesstr))
                    {
                        if($val == 0){
                            $val = count($id) - 1;
                        }
                        else{
                            $val--;
                        }
                    }
                    break;
                case 5:
                    if ($counts[4] * 10 + 5 + $counts[0] * 10 >= $guesstr)
                    {
                        if($val == 0){
                            $val = count($id) - 1;
                        }
                        else{
                            $val--;
                        }
                    }
                    break;
                case 6:
                    if (!($counts[5] * 10 + 6 + $counts[0] * 10 >= $guesstr))
                    {
                        if($val == 0){
                            $val = count($id) - 1;
                        }
                        else{
                            $val--;
                        }
                    }
                    break;
            }
            $newcube = $cubes[$val] - 1;
            if($newcube == 0){
                $sql = 'INSERT INTO "eventtable" ("ide", "guess", "who") VALUES (0, -2, ' . $id[$val] . ')';
            }
            else{
                $sql = 'INSERT INTO "eventtable" ("ide", "guess", "who") VALUES (0, -1, ' . $id[$val] . ')';
            }
            echo $sql;
            echo $val;
            echo $id[$val];
            $db->exec('BEGIN');
            $db->query($sql);
            $db->exec('COMMIT');
        }
        function equal($db, $sesid){
            $guessrt = $db->querySingle('SELECT guess FROM eventtable ORDER BY orders DESC LIMIT 1');
            $result = $db->query('SELECT id, cubes, numbers FROM game');
            $id = Array();
            $cubes = Array();
            $numbers = Array();
            while($row = $result->fetchArray()){
                $id[] = $row["id"];
                $cubes[] = $row["cubes"];
                $numbers[] = $row["numbers"];
            }
            $result->finalize();
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
                        $sql = 'UPDATE game SET cubes = ' . $cube . ' WHERE id = ' . $_SESSION["id"];
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
                        $sql = 'UPDATE game SET cubes = ' . $cube . ' WHERE id = ' . $_SESSION["id"];
                    }
                    break;
                case 2:
                    if ($counts[1] * 10 + 2 + $counts[0] * 10 == $guesstr && $cube < 5)
                    {
                        $cube++;
                        echo "increased";
                        $sql = 'UPDATE game SET cubes = ' . $cube . ' WHERE id = ' . $_SESSION["id"];
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
                        $sql = 'UPDATE game SET cubes = ' . $cube . ' WHERE id = ' . $_SESSION["id"];
                    }
                    break;
                case 3:
                    if ($counts[2] * 10 + 3 + $counts[0] * 10 == $guesstr && $cube < 5)
                    {
                        $cube++;
                        echo "increased";
                        $sql = 'UPDATE game SET cubes = ' . $cube . ' WHERE id = ' . $_SESSION["id"];
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
                        $sql = 'UPDATE game SET cubes = ' . $cube . ' WHERE id = ' . $_SESSION["id"];
                    }
                    break;
                case 4:
                    if ($counts[3] * 10 + 4 + $counts[0] * 10 == $guesstr && $cube < 5)
                    {
                        $cube++;
                        echo "increased";
                        $sql = 'UPDATE game SET cubes = ' . $cube . ' WHERE id = ' . $_SESSION["id"];
                    }
                    elseif ($counts[3] * 10 + 1 + $counts[0] * 10 == $guesstr && !$cubes < 5){
                        echo "max cubes";
                        $sql = '';
                        break;
                    }
                    else
                    {
                        $cube--;
                        echo "decreased";
                        $sql = 'UPDATE game SET cubes = ' . $cube . ' WHERE id = ' . $_SESSION["id"];
                    }
                    break;
                case 5:
                    if ($counts[4] * 10 + 5 + $counts[0] * 10 == $guesstr && $cube < 5)
                    {
                        $cube++;
                        echo "increased";
                        $sql = 'UPDATE game SET cubes = ' . $cube . ' WHERE id = ' . $_SESSION["id"];
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
                        $sql = 'UPDATE game SET cubes = ' . $cube . ' WHERE id = ' . $_SESSION["id"];
                    }
                    break;
                case 6:
                    if ($counts[5] * 10 + 6 + $counts[0] * 10 == $guesstr && $cube < 5)
                    {
                        $cube++;
                        echo "increased";
                        $sql = 'UPDATE game SET cubes = ' . $cube . ' WHERE id = ' . $_SESSION["id"];
                    }
                    elseif ($counts[5] * 10 + 1 + $counts[0] * 10 == $guesstr && !$cubes < 5){
                        echo "max cubes";
                        $sql = '';
                        break;
                    }
                    else
                    {
                        $cube--;
                        echo "decreased";
                        $sql = 'UPDATE game SET cubes = ' . $cube . ' WHERE id = ' . $_SESSION["id"];
                    }
                    break;
            }
            $db->exec('BEGIN');
            $db->query($sql);
            $db->exec('COMMIT');
            if($cube < $cubes[$val]){
                if($cube == 0){
                    $sql = 'INSERT INTO "eventtable" (ide, guess, who) VALUES (0, -2, ' . $id[$val] . ')';
                }
                else{
                    $sql = 'INSERT "eventtable" (ide, guess, who) VALUES (0, -1, ' . $id[$val] . ')';
                }
            }
            elseif($cube > $cubes[$val]){
                $sql = 'INSERT "eventtable" (ide, guess, who) VALUES (0, 1, ' . $id[$val] . ')';
            }
            else{
                $sql= '';
            }
            $db->exec('BEGIN');
            $db->query($sql);
            $db->exec('COMMIT');
        }
        function guess($db, $newguess){
            $guess = $db->querySingle('SELECT guess FROM eventtable ORDER BY orders DESC LIMIT 1');
            if(substr(strval($newguess), -1) == 0 || substr(strval($newguess), -1) == 7 || substr(strval($newguess), -1) == 8 || substr(strval($newguess), -1) == 9){
                echo "invalid input";
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
            if(is_string($guess)){
                $guess = 10;
            }
            $ng = $newguess/$b;
            $lg = $guess/$a;
            if($ng > $lg){
                $result = $db->query('SELECT id, cubes FROM game');
                $id = Array();
                $cubes = Array();
                while($row = $result->fetchArray()){
                    $id[] = $row["id"];
                    $cubes[] = $row["cubes"];
                }
                $result->finalize();
                unset($row);
                $key = array_search(0, $cubes);
                while(is_int($key)){
                    unset($id[$key]);
                    unset($cubes[$key]);
                    $key = array_search(0, $cubes);
                }
                $cubes = array_values(array_filter($cubes));
                $id = array_values(array_filter($id));
                $cpkey = array_search($_SESSION["id"], $id);
                $cpkey++;
                if(empty($id[$cpkey])){
                    $cpkey = 0;
                }
                $db->exec('BEGIN');
                $db->query('UPDATE "game" SET cPlayerId = ' . $id[$cpkey] . ' WHERE id = 0');
                $db->exec('COMMIT');
            }
            else{
                header('Location: guessTurn.php');
                die("too small input");
            }
        }
        function isover($db){
            $result = $db->query('SELECT id, cubes FROM game');
            $id = Array();
            $cubes = Array();
            while($row = $result->fetchArray()){
                $id[] = $row["id"];
                $cubes[] = $row["cubes"];
            }
            $result->finalize();
            unset($row);
            $key = array_search(0, $cubes);
            while(is_int($key)){
                unset($id[$key]);
                unset($cubes[$key]);
                $key = array_search(0, $cubes);
            }
            $cubes = array_values(array_filter($cubes));
            $id = array_values(array_filter($id));
            if(count($id) < 2){
                //gameover
                $db->exec('BEGIN');
                $db->query('UPDATE "game" SET cycle = 2 WHERE id = 0');
                $db->exec('COMMIT');
                header('Location: winpage.php');
                die("game is over");
            }
        }
    ?>
    <p id="a"></p>
    <script defer type="text/javascript">
        var reload = '<?php echo $succes; ?>'; //php runner, redirecter
        document.getElementById("a").innerHTML = reload;
        switch(reload){
            case 1:
                location.href = 'waitingForTurn.php';
                break;
            case 2:
                location.href = 'guessTurn.php';
                break;
        }
    </script>
</body>
</html>