<html>
<head>
    <link rel="stylesheet" href="../dark_theme.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        $sql = "SELECT playersInGame FROM game WHERE id = 0";
	    $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $into = $row["playersInGame"] + 1;
            echo $into;
            $sql = "UPDATE game SET name = '" . $_GET["username"] . "' WHERE id = " . $into;
            if (mysqli_query($conn, $sql)) {
                echo "user registered succesfully";
                $sql = "UPDATE game SET playersInGame = " . $into . " WHERE id = 0";
                if (mysqli_query($conn, $sql)){
                    echo "done";
                    $whatnow = 0;
                }
                else{
                    $whatnow = 1;
                }
            }
            else{
                echo "coudn't record player";
                $whatnow = 1;
            }
        }
        else {
            echo "no result/operational error";
            $whatnow = 1;
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
    <script defer type="text/javascript">
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
        var id = '<?php echo $into; ?>';
        if(reload == 0){
            location.href = 'waitingForTurn.php?id=' + id + '&username=' + username;
        }
        else {
            setTimeout(function(){location.reload()}, 15000);
        }
    </script>
</body>
</html>