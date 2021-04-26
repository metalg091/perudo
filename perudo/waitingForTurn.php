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
        $sql = "SELECT name FROM game WHERE id BETWEEN 1 AND " . $_SESSION['PIG'];
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0){
            $others = Array();
            while($row = mysqli_fetch_assoc($result)){
                $others[] = $row["name"];
            }
            $outOthers = json_encode($others);
        }
        else{

        }
        mysqli_close($conn);
    ?>
    <h2>Your name</h2>
    <h2>Other players</h2>
    <h2>Cubes in game</h2>
    <h2>Ur numbers</h2>
    <p id="numbers">123456</p>
    <script type="text/javascript">
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
        if(reload == 0){
            location.href = "waitingForTurn.php";
        }
        else {
            setTimeout(function(){location.reload()}, 15000);
        }
        var array = new Array();
        array = <?php echo $outOthers; ?>;
        document.getElementById("others").appendChild(listOfOthers(array));
    </script>
</body>
</html>