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
        unset($neworderid);

        roll($conn);
        function roll($conn){
            $sql = "SELECT name, cubes FROM game";
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) > 0){
                $name = Array();
                $cubes = Array();
                while($row = mysqli_fetch_assoc($result)){
                    $name[] = $row["name"];
                    $cubes[] = $row["cubes"];
                }
            }
            unset($row);
            $key = array_search(0, $cubes);
            while(is_int($key)){
                unset($name[$key]);
                unset($cubes[$key]);
                $key = array_search(0, $cubes);
            }
            unset($key);
            gc_collect_cycles();
            $num = Array();
            $numstr = "";
            foreach($name as $player){
                for ($x = 0; $cubes>$x; $x++){
                    $num[] = random_int(1, 6);
                    $numstr = $numstr . $num[$x];
                }
                $sql = "UPDATE game SET numbers = '" +  $numstr + "' WHERE name = '" + $player + "'";
                if(mysqli_query($conn, $sql)){
                    echo "roll success";
                }
                else{
                    echo "error";
                }
            }
        }
        mysqli_close($conn);
    ?>
    <p id="a">aaa</p>
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