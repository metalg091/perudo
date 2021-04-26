<html>
<head>
    <link rel="stylesheet" href="dark_theme.css">
    <style>
        #username{
            margin: 0 0 0 30px;
            color: lightblue;
        }
    </style>
    <!--<meta id="meta" http-equiv="refresh" content="">-->
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
        $sql = "SELECT cycle, playersInGame FROM game WHERE id = 0";
	    $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            if ($row["cycle"] == 1){
                $into = $row["playersInGame"] + 1;
                $sql = "UPDATE game SET name = '" . $_GET["username"] . "' where id = " . $into;
                if (mysqli_query($conn, $sql)) {
                    echo "user registered succesfully";
                    $sql = "UPDATE game SET cycle = 0, playersInGame = '" . $into . "' WHERE id=0";
                    if (mysqli_query($conn, $sql)) {
                        echo "<br> done";
                        $whatnow = 0;
                    }
                    else {
                        echo "system message wasn't passed down";
                        $whatnow = 1;
                    }
                }
                else{
                    echo "coudn't record player";
                    $whatnow = 1;
                }
            }
            else{
                echo "reload page/waiting for my turn";
                $whatnow = 1;
            }
        }
        else {
            echo "no result/operational error";
            $whatnow = 1;
        }
        $sql = "SELECT name FROM game WHERE id BETWEEN 1 AND " . $row["playersInGame"];
        $result = mysqli_query($conn, $sql);
        $_SESSION['PIG'] = $row["playersInGame"];
        if (mysqli_num_rows($result) > 0){
            $others = Array();
            while($row = mysqli_fetch_assoc($result)){
                $others[] = $row["name"];
            }
            $outOthers = json_encode($others);
        }
        else{

        }
        mysqli_close($conn);
    ?>
    <h2>Your name</h2>
    <h3 id="username">
    <h2>Other players</h2>
    <div id="others">
        
    </div>
    <h2>Cubes in game</h2>
    <p id="cubes">--</p>
    <h2>Ur numbers</h2>
    <p id="numbers">------</p>
    <h1>Please wait until your are registered!!!! (user registration in progress...)</h1>
    <!--<form action="server_connecter.php" method="post">
        <label for="guess">Your guess:</label>
        <input type="radio" name="iguess" value="doubt">Doubt
        <input type="radio" name="iguess" value="equal">Equal
        <input type="radio" name="iguess">Number
        <input type="text" id="guess" name="guess"><br><br>
        <input type="submit" value="Submit">
    </form>-->
    <script type="text/javascript">
        function listOfOthers(names){
            var list = document.createElement('ul');
            for (var i = 0; i < names.length; i++){
                var item = document.createElement('li');
                item.appendChild(document.createTextNode(names[i]));
                list.appendChild(item);
            }
            return list;
        }
        var username = '<?php echo $_GET["username"]; ?>';
        document.getElementById("username").innerHTML = username;
        var reload = '<?php echo $whatnow; ?>';
        if(reload == 0){
            location.href = "waitingForTurn.php?username="<?php echo $username; ?>;
        }
        else {
            setTimeout(function(){location.reload()}, 15000);
        }
        var array = new Array();
        array = <?php echo $outOthers; ?>;
        document.getElementById("others").appendChild(listOfOthers(array));
    </script>
</body>
</html>