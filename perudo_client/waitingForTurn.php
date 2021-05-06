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
    <h2>Your name</h2>
    <h3 id="username"></h3>
    <h2>Other players</h2>
    <div id="others">
    </div>
    <h2>Cubes in game</h2>
    <p id="allcubes"></p>
    <h2>Ur numbers</h2>
    <p id="urnumbers">------</p>
    <iframe src="eventGetter.php" id="eventGetter" width="100%" height="1000px">
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
        function tableGenrator (names, cubes){
            var table = document.createElement("table");
            for(let i = 0; i < names.length; i++){
                var row = document.createElement("tr");
                var tdname = document.createElement("td");
                var tdcube = document.createElement("td");
                var textcube = document.createTextNode(cubes[i]);
                var textname = document.createTextNode(names[i]);
                tdname.appendChild(textname);
                tdcube.appendChild(textcube);
                row.appendChild(tdname);
                row.appendChild(tdcube);
                table.appendChild(row);
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

        function getSum(num){
            var sol = 0;
            for(let i = 0; i<num.length; i++){
                sol = sol + parseInt(num[i]);
            }
            return sol;
        }
    </script>
</body>
</html>