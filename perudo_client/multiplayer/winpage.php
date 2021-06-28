<html>
<head>
    <link id="theme" rel="stylesheet" href="../main_theme.css">
    <script src="../themeSwitch.js"></script>
    <script type="text/javascript">
        <?php
            echo "themeSetup(" . $_COOKIE["theme"] . ");";
        ?>
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div id="winner">
<?php 
    $db = new SQLite3('../databases/perudo.sqlite', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
    //$cycle = $db->querySingle('SELECT cycle FROM "game" WHERE id = 0');
    /*if($cycle != 2 || $cycle != null){
        header('Location: waitingForTurn.php');
        die('Game is still ongoing!!!');
    }*/
    $winner = $db->querySingle('SELECT name FROM "winners" ORDER BY id LIMIT 1');
    echo $winner . " is the winner";
    @tabledropper($db, $winner);
    function tabledropper($db, $winner){
        $result = $db->query('SELECT name, cubes FROM game');
        $name = Array();
        $cubes = Array();
        if(!$result){
            die("");
        }
        while($row = $result->fetchArray()){
            $name[] = $row["name"];
            $cubes[] = $row["cubes"];
        }
        $result->finalize();
        unset($row);
        $key = array_search(0, $cubes);
        while(is_int($key)){
            unset($name[$key]);
            unset($cubes[$key]);
            $key = array_search(0, $cubes);
        }
        unset($cubes);
        $name = array_values(array_filter($name));
        if($name[0] == $winner){
            $db->query('DROP TABLE "game"');
            $db->query('DROP TABLE "eventtable"');
        }
    }
?>
</div>
</body>
</html>