<html>
<head>
    <link id="theme" rel="stylesheet" href="dark_theme.css">
    <link rel="stylesheet" href="button.css">
    <script src="themeSwitch.js"></script>
    <style>
        h2{
            padding: 6.25%;
            text-align: center;
        }
        form {
            text-align: center;
        }
    </style>
</head>
<body>
<label class="switch">
    <input class="toggle-state" type="checkbox" name="check" value="check" onchange="themeSwitch()"/><div></div>
</label>
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
</body>
</html>
