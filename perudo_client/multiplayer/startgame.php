<html>
    <head>

    </head>
    <body>
        <?php
            $db = new SQLite3('../databases/perudo.sqlite', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
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
                $num = null;
            }
            $db->exec('BEGIN');
            $db->query('UPDATE "game" SET cycle = 0 WHERE id = 0');
            $db->exec('COMMIT');
            header('Location: waitingforturn.php');
        ?>
    </body>
</html>