<?php
session_start();
?>
<html>
<head>
    <link rel="stylesheet" href="<?php echo $_COOKIE["theme"]?>" id="theme">
    <link rel="stylesheet" href="../button.css">
    <script src="../themeSwitch.js"></script>
    <script src="otherPlayers.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php 
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "perudo";
        $conn = mysqli_connect($servername, $username, $password, $dbname);
        if (!$conn){
            die("Connection failed: " . mysqli_connect_error());
        }
        $sql = "SELECT cycle FROM game WHERE id = 0";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $cycle = $row["cycle"];
        $sql = "SELECT playersInGame, cPlayerId FROM game WHERE id = 0";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
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
        $sql = "SELECT guess FROM eventtable ORDER BY orders DESC LIMIT 1";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $lastguess = substr($row["guess"], 0, -1);
        $rellastguess = $row["guess"];
        $sql = "SELECT cubes, numbers FROM game WHERE id = " . $_SESSION["id"];
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        mysqli_close($conn);
    ?>
</head>
<body>
    <!--button id="themb" onclick="document.getElementById('eventGetter').src += '';">refresh</button-->
    <label class="switch">
        <input class="toggle-state" type="checkbox" name="check" value="check" onchange="themeSwitch()" checked/><div></div>
    </label>
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
        <input type="text" value="<?php echo $lastguess; ?>" id="guess" name="guess1" onkeyup="inputValidator()">
        <input type="number" value="1" id="guess1" name="guess2" min="1" max="6" onkeyup="inputValidator()"><br><br>
        <input id="submit" type="submit" value="Submit" style="display: none">
    </form>
    </div>
    <h2 id="iframeTitle">Events:</h2>
    <iframe src="eventGetter.php?style=../dark_theme.css" id="eventGetter">
    </iframe>
    <script defer type="text/javascript">
        var a = '<?php echo $cycle; ?>';
        if(a == '2'){
            location.href = 'winpage.php';
        }
        var id = '<?php echo $_SESSION["id"] ?>';
        var username = '<?php echo $_SESSION["username"]; ?>'; //getting info specific to this user
        document.getElementById("username").innerHTML = username + " your id is " + id;
        var nums = '<?php echo $row["numbers"]; ?>';
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
    
        inputValidator();

        function inputValidator(){ //onyl shows submit button when input is legit (larger than lastguess)
            var times = parseInt(document.getElementById("guess").value);
            var number = parseInt(document.getElementById("guess1").value);
            var rellastguess = '<?php echo $rellastguess; ?>';
            var lastguess = '<?php echo $lastguess; ?>';
            if(rellastguess > 10){
            
            }else{
                rellastguess = 10;
                lastguess = 0;
                document.getElementById("raddiv").style.display = "none";
            }
        
            var lastguesslastnum = rellastguess - lastguess * 10;
            var relnum = parseInt(times*10+number);
            console.log(rellastguess + " and " + relnum);
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