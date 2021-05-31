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
                $sql = "INSERT INTO eventtable (orders, ide, guess) VALUES (" . $neworderid . ", " . $_SESSION["id"] . ", " . $guess . ")";
                break;
            case 1:
                $sql = "INSERT INTO eventtable (orders, ide, guess) VALUES (" . $neworderid . ", " . $_SESSION["id"] . ", '''doubt''')";
                doubt($conn);
                roll($conn);
                break;
            case 2:
                $equal = "equal";
                $sql = "INSERT INTO eventtable (orders, ide, guess) VALUES (" . $neworderid . ", " . $_SESSION["id"] . ", '''" . $equal . "''')";
                roll($conn);
                break;
        }
        if(mysqli_query($conn, $sql)){
            echo "succes <br>";
            $sql = "UPDATE `game` SET `cycle`= 0 WHERE id = 0";
            if(mysqli_query($conn, $sql)){
                echo "succes again <br>";
                $succes = true;
            }
            else{
                echo "Error updating record: " . mysqli_error($conn);
                $succes = false;
            }
        }
        else{
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            echo "I will try again in 15 seconds!";
            $succes = false;
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
        function doubt($conn){
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
            $id = array_values(array_filter($id));

            switch (substr($guesstr, -1))
            {
                case 1:
                    if (n1 * 10 + 1 >= lastguess)
                    {
                        cplayer.cubes--;
                    }
                    else
                    {
                        lplayer.cubes--;
                    }
                    break;
                case 2:
                    if (n2 * 10 + 2 + n1 * 10 >= lastguess)
                    {
                        cplayer.cubes = cplayer.cubes - 1;
                    }
                    else
                    {
                        lplayer.cubes = lplayer.cubes - 1;
                    }
                    break;
                case 3:
                    if (n3 * 10 + 3 + n1 * 10 >= lastguess)
                    {
                        cplayer.cubes = cplayer.cubes - 1;
                    }
                    else
                    {
                        lplayer.cubes = lplayer.cubes - 1;
                    }
                    break;
                case 4:
                    if (n4 * 10 + 4 + n1 * 10 >= lastguess)
                    {
                        cplayer.cubes = cplayer.cubes - 1;
                    }
                    else
                    {
                        lplayer.cubes = lplayer.cubes - 1;
                    }
                    break;
                case 5:
                    if (n5 * 10 + 5 + n1 * 10 >= lastguess)
                    {
                        cplayer.cubes = cplayer.cubes - 1;
                    }
                    else
                    {
                        lplayer.cubes = lplayer.cubes - 1;
                    }
                    break;
                case 6:
                    if (n6 * 10 + 6 + n1 * 10 >= lastguess)
                    {
                        cplayer.cubes = cplayer.cubes - 1;
                    }
                    else
                    {
                        lplayer.cubes = lplayer.cubes - 1;
                    }
                    break;
            }*/
        }
        mysqli_close($conn);
    ?>
    <p id="a"></p>
    <script defer type="text/javascript">
        var reload = '<?php echo $succes; ?>'; //php runner, redirecter
        document.getElementById("a").innerHTML = reload;
        if(reload){
            //location.href = 'waitingForTurn.php';
        }
        else {
            //setTimeout(function(){location.reload()}, 15000);
        }
    </script>
</body>
</html>