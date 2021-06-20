<html>
    <head>
        <script src="otherPlayers.js"></script>
        <!--script src="../themeSwitch.js"></script-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body id="event" style="background-color: <?php if(isset($_COOKIE["eventbgc"])){echo $_COOKIE["eventbgc"];} ?>; color: <?php if(isset($_COOKIE["txtc"])){echo $_COOKIE["txtc"];}else{if($_COOKIE["theme"] == 2){echo "black";}else{echo "#fff";}} ?>;">
    <?php
        /*$servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "perudo";
        $conn = mysqli_connect($servername, $username, $password, $dbname);
        if (!$conn){
            die("Connection failed: " . mysqli_connect_error());
        }*/
        $db = new SQLite3('../databases/perudo.sqlite', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
        $db->query('CREATE TABLE IF NOT EXISTS "game" (
            "id" INTEGER UNIQUE PRIMARY KEY AUTOINCREMENT NOT NULL,
            "name" TEXT,
            "cubes" INTEGER DEFAULT "5",
            "numbers" INTEGER DEFAULT "12345",
            "cPlayerId" INTEGER DEFAULT null,
            "playersInGame" INTEGER DEFAULT null,
            "cycle" INTEGER DEFAULT null)');
        $pig = $db->querySingle('SELECT "playersInGame" cPlayerId FROM "game" WHERE id = 0');
        //$cpi = $db->querySingle('SELECT "cPlayerId" FROM "game" WHERE id = 0');//change $row["cPlayerId"] to cpi; there isn't even a reference to it bruh 😒
        $results = $db->query('SELECT name FROM "game" WHERE id BETWEEN 1 AND ' . $pig);
        $names = Array();
        while($row = $results->fetchArray()){
            $names[] = $row["name"];
        }
        $results->finalize();
        $outName = json_encode($names);
        $names = null;
        $lines = round(($_GET["height"] - 40)/20);
        $results = $db->query('SELECT * FROM "eventtable" ORDER BY orders DESC LIMIT ' . $lines);
        $guess = Array();
        $eventId = Array();
        $who = Array();
        while($row = $results->fetchArray()){
            $guess[] = $row["guess"];
            $eventId[] = $row["ide"];
            $who[]= $row["who"];
        }
        $results->finalize();
        $guess = array_reverse($guess);
        $eventId = array_reverse($eventId);
        $who = array_reverse($who);
        /*for ($i = 0; $i < count($guess); $i++){
            $guess[$i] = str_replace("'", "", $guess[$i]); //It was necessairy for mysql
        }*/
        $arrayofremove = Array();
        $outGuess = json_encode($guess);
        $guess = null;
        $outEventId = json_encode($eventId);
        $eventId = null;
        $outWho = json_encode($who);
        $who = null;
        if(empty($outGuess)){
            echo "Game hasn't started yet!";
        }
    ?>
    <script defer type="text/javascript">
        var arrayOfNames = arraymaker('<?php echo $outName; ?>');
        var arrayOfIde = arraymaker('<?php echo $outEventId; ?>');
        var arrayOfGuess = arraymaker('<?php echo $outGuess; ?>');
        var arrayOfWho = arraymaker('<?php echo $outWho; ?>');
        var arrayOfIWho = [];
        for(var p = 0; p < arrayOfWho.length; p++){
            arrayOfIWho[p] = arrayOfNames[arrayOfWho[p]-1]; 
        }
        //console.log(arrayOfWho);
        var arrayOfINames = [];
        var whoid = 0;
        for (var i = 0; i < arrayOfIde.length; i++){
            if (arrayOfIde[i] == 0){
                arrayOfINames[i] = 0;
            }
            else{
                arrayOfINames[i] = arrayOfNames[arrayOfIde[i]-1]; 
            }
        }
        
        document.getElementById("event").appendChild(guessTableGenrator(arrayOfINames, arrayOfGuess));
        function guessTableGenrator (names, guess){
            var table = document.createElement("table");
            for(let i = 0; i < names.length; i++){
                if(names[i] == "0"){
                    switch(guess[i]){
                        case "-1":
                            var CubeEvent = "lost a Cube";
                            var who = arrayOfIWho[i];
                            break;
                        case "-2":
                            var CubeEvent = "lost all of their cubes";
                            var who = arrayOfIWho[i];
                            break;
                        case "1":
                            var CubeEvent = "Got an extra cube";
                            var who = arrayOfIWho[i];
                            break;
                    }
                    var row = document.createElement("tr");
                    var tdname = document.createElement("td");
                    var tdguess = document.createElement("td");
                    var tdtext = document.createElement("td");
                    var textguess = document.createTextNode(CubeEvent);
                    var textname = document.createTextNode(who);
                    tdname.appendChild(textname);
                    tdguess.appendChild(textguess);
                    row.appendChild(tdname);
                    row.appendChild(tdguess);
                    table.appendChild(row);
                }
                else{
                    if(!isNaN(parseInt(guess[i]))){
                        var row = document.createElement("tr");
                        var tdname = document.createElement("td");
                        var tdguess = document.createElement("td");
                        var tdtext = document.createElement("td");
                        var textguess = document.createTextNode(guess[i]);
                        var textname = document.createTextNode(names[i]);
                        var text = document.createTextNode("guessed");
                        tdname.appendChild(textname);
                        tdtext.appendChild(text);
                        tdguess.appendChild(textguess);
                        row.appendChild(tdname);
                        row.appendChild(tdtext);
                        row.appendChild(tdguess);
                        table.appendChild(row);
                    }
                    else{
                        if(guess[i] == "doubt"){
                            var row = document.createElement("tr");
                            var tdname = document.createElement("td");
                            var tdguess = document.createElement("td");
                            var tdtext = document.createElement("td");
                            var tdfrom = document.createElement("td");
                            var textguess = document.createTextNode(guess[i-1]);
                            var textname = document.createTextNode(names[i]);
                            var textfromname = document.createTextNode("from " + names[i-1]);
                            var text = document.createTextNode("doubted the ");
                            tdname.appendChild(textname);
                            tdtext.appendChild(text);
                            tdguess.appendChild(textguess);
                            tdfrom.appendChild(textfromname);
                            row.appendChild(tdname);
                            row.appendChild(tdtext);
                            row.appendChild(tdguess);
                            row.appendChild(tdfrom);
                            table.appendChild(row);
                        }
                        else if(guess[i] == "equal"){
                            var row = document.createElement("tr");
                            var tdname = document.createElement("td");
                            var tdguess = document.createElement("td");
                            var tdtext = document.createElement("td");
                            var textguess = document.createTextNode(guess[i-1]);
                            var textname = document.createTextNode(names[i]);
                            var text = document.createTextNode("thinks there are exactly ");
                            tdname.appendChild(textname);
                            tdtext.appendChild(text);
                            tdguess.appendChild(textguess);
                            row.appendChild(tdname);
                            row.appendChild(tdtext);
                            row.appendChild(tdguess);
                            table.appendChild(row);
                        }
                        else{
                            break;
                        }
                    }
                }
            }
            return table;
        }
        //setTimeout(function(){location.reload()}, 5000);
    </script>
    </body>
</html>