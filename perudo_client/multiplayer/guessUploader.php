<?php
session_start();
?>
<html>
<head>
    <link rel="stylesheet" href="../dark_theme.css">
    <script type="text/javascript">
        var theme = '<?php echo $_COOKIE["theme"]; ?>';
        themeSetup(theme);
    </script>
</head>
<body>
<?php 
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "perudo";
        $conn = mysqli_connect($servername, $username, $password, $dbname);
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
                break;
            case 2:
                $equal = "equal";
                $sql = "INSERT INTO eventtable (orders, ide, guess) VALUES (" . $neworderid . ", " . $_SESSION["id"] . ", '''" . $equal . "''')";
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
        mysqli_close($conn);
    ?>
    <p id="a"></p>
    <script defer type="text/javascript">
        var reload = '<?php echo $succes; ?>'; //php runner, redirecter
        document.getElementById("a").innerHTML = reload;
        if(reload){
            location.href = 'waitingForTurn.php';
        }
        else {
            setTimeout(function(){location.reload()}, 15000);
        }
    </script>
</body>
</html>