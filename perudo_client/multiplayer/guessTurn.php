<?php
session_start();
?>
<html>
<head>
    <link rel="stylesheet" href="../main_theme" id="theme">
    <script src="../themeSwitch.js"></script>
    <script src="otherPlayers.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        isover($db, $cycle);
        $urnumbers = $db->querySingle('SELECT "numbers" FROM "game" WHERE id = ' . $_SESSION["id"] . '');
        $urcubes = $db->querySingle('SELECT "cubes" FROM "game" WHERE id = ' . $_SESSION["id"] . '');
        $playersInGame = $db->querySingle('SELECT "playersInGame" FROM "game" WHERE id = 0');
        $cpi = $db->querySingle('SELECT "cPlayerId" FROM "game" WHERE id = 0');
        if($_SESSION["id"] != $cpi && $cycle != 2){
            header('Location: waitingForTurn.php');
        }
        $results = $db->query('SELECT name, cubes FROM "game" WHERE id BETWEEN 1 AND ' . $playersInGame);
        while($row = $results->fetchArray()){
            $names[] = $row["name"];
            $cubes[] = $row["cubes"];
        }
        $results->finalize();
        $row = null;
        $outOthers = json_encode($names);
        $outCubesOfOthers = json_encode($cubes);
        
        $db->query('CREATE TABLE IF NOT EXISTS "eventtable" (
            "orders" INTEGER UNIQUE PRIMARY KEY AUTOINCREMENT NOT NULL,
            "ide" INTEGER,
            "guess" TEXT,
            "who" INTEGER DEFAULT "0"
            )');

        $rellastguess = $db->querySingle('SELECT "guess" FROM "eventtable" ORDER BY "orders" DESC LIMIT 1');
        $db->close();
        if(is_string(intval($rellastguess))){
            $rellastguess = 10;
        }
        function isover($db, $cycle){
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
            if(count($id) == 1 || $cycle == 2){     //gameover
                $winner = $db->querySingle('SELECT "name" FROM "game" WHERE id = ' . $id[0]);
                $db->query('CREATE TABLE IF NOT EXISTS "winners" (
                    "id" INTEGER UNIQUE PRIMARY KEY AUTOINCREMENT NOT NULL,
                    "name" TEXT)');
                $db->exec('BEGIN');
                $db->query('UPDATE "game" SET cycle = 2 WHERE id = 0');
                $db->query('INSERT OR IGNORE INTO "winners" ("name") VALUES ("' . $winner . '")');
                $db->exec('COMMIT');
                header('Location: winpage.php');
                $db->close();
                die("game is over");
            }
        }
    ?>
</head>
<body onresize="render()">
    <div id="container">
    <h2>Your name</h2>
    <h3 id="username" class="data"></h3>
    <h2>Other players</h2>
    <div id="others" class="data">
    </div>
    <h2>Cubes in game</h2>
    <p id="allcubes" class="data"></p>
    <h2>Your numbers</h2>
    <p id="urnumbers" class="data">------</p>
    <form action="guessUploader.php" method="post">
        <label for="guess">Your guess:</label>
        <div id="raddiv" style="margin: 20px; display: inline;">
        <div id="rad1">
            <input id="radio1" type="radio" name="iguess" value="1" onclick="document.getElementById("raddiv").style.display = "inline";">Doubt
        </div>
        <div id="rad2">
            <input id="radio2" type="radio" name="iguess" value="2">Equal
        </div>
        <div id="rad3">
            <input id="radio3" type="radio" name="iguess" value="3" checked>Number:
        </div>
        </div>
        <!--<input type="text" id="guess" name="guess"><br><br>-->
        <input type="text" value="" id="guess" name="guess1" onkeyup="inputValidator()">
        <input type="number" value="1" id="guess1" name="guess2" min="1" max="6" onkeyup="inputValidator()"><br><br>
        <input id="submit" type="submit" value="Submit" style="display: none">
    </form>
    </div>
    <h2 id="iframeTitle">Events:</h2>
    <iframe src="eventGetter.php?height=741" id="eventGetter">
    </iframe>
    <script defer type="text/javascript">
        <?php
            echo "themeSetup(" . $_COOKIE["theme"] . ");";
        ?>
        render();
        function render(){
            document.getElementById("eventGetter").src = "eventGetter.php?height=" + document.getElementById("eventGetter").clientHeight;
        }
        var a = '<?php echo $cycle; ?>';
        if(a == '2'){
            location.href = 'winpage.php';
        }
        var id = '<?php echo $_SESSION["id"] ?>';
        var username = '<?php echo $_SESSION["username"]; ?>'; //getting info specific to this user
        document.getElementById("username").innerHTML = username + " your id is " + id;
        var nums = '<?php echo $urnumbers; ?>';
        document.getElementById("urnumbers").innerHTML = nums;

        arrayc = arraymaker('<?php echo $outCubesOfOthers; ?>'); //get info from other players
        var array = arraymaker('<?php echo $outOthers; ?>');
        for(var i = -1; i<arrayc.length; i++){
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
        
        function setInputFilter(textbox, inputFilter) { //only allows numeric input
            ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function(event) {
                textbox.addEventListener(event, function() {
                    if (inputFilter(this.value)) {
                        this.oldValue = this.value;
                        this.oldSelectionStart = this.selectionStart;
                        this.oldSelectionEnd = this.selectionEnd;
                    }
                    else if (this.hasOwnProperty("oldValue")) {
                        this.value = this.oldValue;
                        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
                    }
                    else {
                        this.value = "";
                    }
                });
            });
        }

        setInputFilter(document.getElementById("guess"), function(value) {
        return /^\d*$/.test(value); });
        setInputFilter(document.getElementById("guess1"), function(value) {
        return /^\d*$/.test(value); });
    
        var rellastguess = '<?php echo $rellastguess; ?>';
        //console.log(rellastguess.length);
        var lastguess = rellastguess.substr(0, rellastguess.length - 1);
        //console.log(lastguess);
        document.getElementById("guess").value = lastguess;

        inputValidator();
        function inputValidator(){ //onyl shows submit button when input is legit (larger than lastguess)
            var times = parseInt(document.getElementById("guess").value);
            var number = parseInt(document.getElementById("guess1").value);
            var rellastguess = '<?php echo $rellastguess; ?>';
            //console.log(rellastguess);
            var lastguess = rellastguess.substr(0, rellastguess.length - 1);
            //console.log(lastguess);
            
            if(rellastguess > 10){
            
            }else{
                rellastguess = 10;
                //lastguess = 0;
                document.getElementById("raddiv").style.display = "none";
            }
        
            var lastguesslastnum = rellastguess - lastguess * 10;
            var relnum = parseInt(times*10+number);
            if(lastguesslastnum == 1 || number == 1){
                if(lastguesslastnum == 1 && number == 1){
                    if(relnum > rellastguess){
                        document.getElementById("submit").style.display ="block";
                    }
                    else{
                        document.getElementById("submit").style.display ="none";
                    }
                }
                else{
                    if(number == 1){
                        if(relnum > rellastguess/2){
                            document.getElementById("submit").style.display ="block";
                        }
                        else{
                            document.getElementById("submit").style.display ="none";
                        }
                    }
                    else{
                        if(relnum > rellastguess * 2){
                            document.getElementById("submit").style.display ="block";
                        }
                        else{
                            document.getElementById("submit").style.display ="none";
                        }
                    }
                }
            }
            else{
                if(relnum > rellastguess){
                    document.getElementById("submit").style.display ="block";
                }
                else{
                    document.getElementById("submit").style.display ="none";
                }
            }
        }
    </script>
</body>
</html>
