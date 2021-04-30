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
        mysqli_close($conn);
    ?>
    </body>
</html>