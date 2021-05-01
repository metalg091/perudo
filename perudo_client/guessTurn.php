<html>
<head>
    <link rel="stylesheet" href="dark_theme.css" id="theme">
    <link rel="stylesheet" href="button.css">
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
        mysqli_close($conn);
    ?>
</head>
<body>
<label class="switch">
    <input class="toggle-state" type="checkbox" name="check" value="check" onchange="themeSwitch()"/><div></div>
</label>

    <h2>Your name</h2>
    <h3 id="username"></h3>
    <h2>Other players</h2>
    <div id="others">
    </div>
    <h2>Cubes in game</h2>
    <p id="allcubes"></p>
    <h2>Ur numbers</h2>
    <p id="urnumbers">------</p>
    <form action="server_connecter.php" method="post">
        <label for="guess">Your guess:</label>
        <input type="radio" name="iguess" value="doubt">Doubt
        <input type="radio" name="iguess" value="equal">Equal
        <input type="radio" name="iguess">Number
        <input type="text" id="guess" name="guess"><br><br>
        <input type="submit" value="Submit">
    </form>
    <button onclick="document.getElementById('eventGetter').src += '';">refresh</button>
    <iframe src="eventGetter.php" id="eventGetter" width="100%" height="1000px">
    </iframe>
    <script defer type="text/javascript">
        var theme = 0;
        var id = '<?php echo $_GET["id"] ?>';
        var username = '<?php echo $_GET["username"]; ?>'; //getting info specific to this user
        document.getElementById("username").innerHTML = username + " your id is " + id;
        var nums = '<?php echo $row["numbers"]; ?>';
        document.getElementById("urnumbers").innerHTML = nums;

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

        function themeSwitch(){
            if(theme == 0){
                document.getElementById("theme").setAttribute("href", "light_theme.css");3
                theme++;
            }
            else{
                document.getElementById("theme").setAttribute("href", "dark_theme.css");
                theme--;
            }
        }
    </script>
</body>
</html>