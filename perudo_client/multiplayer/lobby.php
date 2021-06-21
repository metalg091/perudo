<?php
session_start();
?>
<html>
<head>
    <link id="theme" rel="stylesheet" href="../main_theme.css">
    <script src="../themeSwitch.js"></script>
    <script src="otherPlayers.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body onresize="render()">
    <?php 
        $db = new SQLite3('../databases/perudo.sqlite', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
        $db->query('CREATE TABLE IF NOT EXISTS "game" (
            "id" INTEGER UNIQUE PRIMARY KEY AUTOINCREMENT NOT NULL,
            "name" TEXT,
            "cubes" INTEGER,
            "numbers" INTEGER,
            "cPlayerId" INTEGER DEFAULT null,
            "playersInGame" INTEGER DEFAULT null,
            "cycle" INTEGER DEFAULT null)');

        $cycle = $db->querySingle('SELECT "cycle" FROM "game" WHERE id = 0');
        $urnumbers = $db->querySingle('SELECT "numbers" FROM "game" WHERE id = ' . $_SESSION["id"]);
        $urcubes = $db->querySingle('SELECT "cubes" FROM "game" WHERE id = ' . $_SESSION["id"]);
        $playersInGame = $db->querySingle('SELECT "playersInGame" FROM "game" WHERE id = 0');
        $results = $db->query('SELECT name, cubes FROM "game" WHERE id BETWEEN 1 AND ' . $playersInGame);
        while($row = $results->fetchArray()){
            $names[] = $row["name"];
            $cubes[] = $row["cubes"];
        }
        $outOthers = json_encode($names);
        $outCubesOfOthers = json_encode($cubes);
        $db->close();
    ?>
    <div id="container">
        <h2>Your name</h2>
        <h3 id="username" class="data"></h3>
        <h2>Other players</h2>
            <div id="others" class="data">
            </div>
        <h2>Cubes in game</h2>
        <p id="allcubes" class="data"></p>
        <h2>Your numbers</h2>
        <p id="urnumbers" class="data">you have lost all your cubes...</p>
        <?php if($_SESSION["id"] == 1){
            echo '<a href="guessUploader.php">START GAME</a>';
        } ?>
    </div>
    <h2 id="iframeTitle">Events:</h2>
    <iframe src="eventGetter.php?height=741" id="eventGetter">
    </iframe>
    <script type="text/javascript" defer>
        var cycle = '<?php echo $cycle; ?>';
        if (cycle != 6){
            location.href = 'waitingForTurn.php';
        }
        else {
            setTimeout(function(){location.reload()}, 7500);
        }
        render();
        function render(){
            document.getElementById("eventGetter").src = "eventGetter.php?height=" + document.getElementById("eventGetter").clientHeight;
        }
        <?php
            echo "themeSetup(" . $_COOKIE["theme"] . ");";
        ?>
        var a = '<?php echo $cycle; ?>';
        if(a == '2'){
            location.href = 'winpage.php';
        }
        var id = '<?php echo $_SESSION["id"] ?>';
        var username = '<?php echo $_SESSION["username"]; ?>'; //getting info specific to this user
        document.getElementById("username").innerHTML = username + " your id is " + id;
        var nums = '<?php echo $urnumbers; ?>';
        document.getElementById("urnumbers").innerHTML = nums;

        var arrayc = arraymaker('<?php echo $outCubesOfOthers; ?>'); //get info from other players
        var array = arraymaker('<?php echo $outOthers; ?>');
        for(var i = 0; i<arrayc.length; i++){
            var lost = arrayc.indexOf('0');
            if(lost == -1){
                continue;
            }
            else{
                arrayc.splice(lost, 1);
                array.splice(lost, 1);
            }
        }
        document.getElementById("others").appendChild(tableGenrator(array, arrayc));
        document.getElementById("allcubes").innerHTML = getSum(arrayc);
    </script>
</body>
</html>