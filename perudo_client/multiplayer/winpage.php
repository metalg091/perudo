<html>
<head>
    <link id="theme" rel="stylesheet" href="../main_theme.css">
    <link rel="stylesheet" href="../button.css">
    <script src="../themeSwitch.js"></script>
    <script type="text/javascript">
        <?php
            echo "themeSetup(" . $_COOKIE["theme"] . ");";
        ?>
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div id="winner">
<?php 
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "perudo";
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn){
        die("Connection failed: " . mysqli_connect_error());
    }
    $sql = "SELECT cPlayerId FROM game WHERE id = 0";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $who = $row["cPlayerId"];
    $sql = "SELECT name FROM game WHERE id =" . $who;
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    echo $row["name"] . " is the winner";
?>
</div>
<div id="eventGetter"></div>
</body>
</html>