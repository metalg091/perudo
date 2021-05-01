<html>
    <head>
        <link rel="stylesheet" href="dark_theme.css">
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
            echo "error";
        }
        $sql = "SELECT * FROM eventtable";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0){
            $guess = Array();
            $eventId = Array();
            while($row = mysqli_fetch_assoc($result)){
                $guess[] = $row["guess"];
                $eventId[] = $row["ide"];
            }
            $outGuess = json_encode($guess);
            $outEventId = json_encode($eventId);
        }
        else{
            echo "error";
        }
        mysqli_close($conn);
    ?>
    <h2>Events</h2>
    <div id="event">

    </div>
    <script defer type="text/javascript">
        //setTimeout(function(){location.reload()}, 15000);
        var arrayOfNames = arraymaker('<?php echo $outName; ?>');
        var arrayOfIde = arraymaker('<?php echo $outEventId; ?>');
        var arrayOfGuess = arraymaker('<?php echo $outGuess; ?>');
        var arrayOfINames = []
        for (var i = 0; i < arrayOfIde.length; i++){
            arrayOfINames[i] = arrayOfNames[arrayOfIde[i]-1]; 
        }
        document.getElementById("event").appendChild(tableGenrator(arrayOfINames, arrayOfGuess));
        function tableGenrator (names, guess){
            var table = document.createElement("table");
            for(let i = 0; i < names.length; i++){
                if(names == "0"){

                }
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
            return table;
        }
        function arraymaker(newarray){ //makes array from php string output
            newarray = newarray.replace("[", "");
            newarray = newarray.replace("]", "");
            newarray = newarray.replaceAll('"', "");
            newarray = newarray.split(",");
            return newarray;
        }
    </script>
    </body>
</html>