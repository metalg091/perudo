<html>
    <head>
        <link rel="stylesheet" href="dark_theme.css">
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
        $sql = "SELECT playersInGame, cPlayerId FROM game WHERE id = 0";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $sql = "SELECT name FROM game WHERE id BETWEEN 1 AND " . $row["playersInGame"];
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0){
            $names = Array();
            while($row = mysqli_fetch_assoc($result)){
                $names[] = $row["name"];
            }
            $outName = json_encode($names);
        }
        else{
            echo "Game hasn't started yet!";
        }
        $sql = "SELECT * FROM eventtable";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0){
            $guess = Array();
            $eventId = Array();
            $who = Array();
            while($row = mysqli_fetch_assoc($result)){
                $guess[] = $row["guess"];
                $eventId[] = $row["ide"];
                $who[]= $row["who"];
            }
            for ($i = 0; $i < count($guess); $i++){
                $guess[$i] = str_replace("'", "",$guess[$i]);
            }
            $arrayofremove = Array();
            $outGuess = json_encode($guess);
            $outEventId = json_encode($eventId);
            $outWho = json_encode($who);
        }
        else{
            echo "Game hasn't started yet!";
        }
        mysqli_close($conn);
    ?>
    <h2>Events</h2>
    <div id="event">

    </div>
    <script defer type="text/javascript">
        var arrayOfNames = arraymaker('<?php echo $outName; ?>');
        var arrayOfIde = arraymaker('<?php echo $outEventId; ?>');
        var arrayOfGuess = arraymaker('<?php echo $outGuess; ?>');
        var arrayOfWho = arraymaker('<?php echo $outWho; ?>');
        var arrayOfIWho = [];
        for(var p = 0; p < arrayOfWho.length; p++){
            arrayOfIWho[p] = arrayOfNames[arrayOfWho[p]-1]; 
        }
        console.log(arrayOfWho);
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
                            //whoid++;
                            break;
                        case "-2":
                            var CubeEvent = "lost all of their cubes";
                            var who = arrayOfIWho[i];
                            //whoid++;
                            break;
                        case "1":
                            var CubeEvent = "Got an extra cube";
                            var who = arrayOfIWho[i];
                            //whoid++;
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
        setTimeout(function(){location.reload()}, 5000);
    </script>
    </body>
</html>