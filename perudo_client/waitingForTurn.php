<html>
<head>
    <link id="theme" rel="stylesheet" href="dark_theme.css">
    <link rel="stylesheet" href="button.css">
    <script src="themeSwitch.js"></script>
    <script src="otherPlayers.js"></script>
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
        $sql = "SELECT playersInGame, cPlayerId, cycle FROM game WHERE id = 0";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $cpi = $row["cPlayerId"];
        $cycle = $row["cycle"];
        $sql = "SELECT name, cubes FROM game WHERE id BETWEEN 1 AND " . $row['playersInGame'];
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0){
            $others = Array();
            $cubesOfOthers = Array();
            while($row = mysqli_fetch_assoc($result)){
                $cubesOfOthers[] = $row["cubes"];
                $others[] = $row["name"];
            }
            $outOthers = json_encode($others);
            $outCubesOfOthers = json_encode($cubesOfOthers);
        }
        else{

        }
        $sql = "SELECT cubes, numbers FROM game WHERE id = " . $_GET["id"];
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        if($cpi == $_GET["id"] && $cycle == 1){
            $whatnow = true;
        }
        else{
            $whatnow = false;
        }
        mysqli_close($conn);
    ?>
    <label class="switch">
        <input class="toggle-state" type="checkbox" name="check" value="check" onchange="themeSwitch()"/><div></div>
    </label>
    <h2>Your name</h2>
    <h3 id="username" class="data"></h3>
    <h2>Other players</h2>
    <div id="others" class="data">
    </div>
    <h2>Cubes in game</h2>
    <p id="allcubes" class="data"></p>
    <h2>Ur numbers</h2>
    <p id="urnumbers" class="data">------</p>
    <iframe src="eventGetter.php" id="eventGetter">
    </iframe>
    <script defer type="text/javascript">
        var id = '<?php echo $_GET["id"] ?>'
        var username = '<?php echo $_GET["username"]; ?>'; //getting info specific to this user
        document.getElementById("username").innerHTML = username + " your id is " + id;
        var nums = '<?php echo $row["numbers"]; ?>';
        document.getElementById("urnumbers").innerHTML = nums;

        var reload = '<?php echo $whatnow; ?>'; //php runner, redirecter
        if(reload){
            location.href = 'guessTurn.php?id=' + id + '&username=' + username;
        }
        else {
            setTimeout(function(){location.reload()}, 15000);
        }

        arrayc = arraymaker('<?php echo $outCubesOfOthers; ?>'); //get info from other players
        var array = arraymaker('<?php echo $outOthers; ?>');
        document.getElementById("others").appendChild(tableGenrator(array, arrayc));
        document.getElementById("allcubes").innerHTML = getSum(arrayc);   
    </script>
</body>
</html>