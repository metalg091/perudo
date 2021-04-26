<html>
<head>
    <link rel="stylesheet" href="dark_theme.css">
</head>
<body>
    <h2>Your name</h2>
    <h2>Other players</h2>
    <h2>Cubes in game</h2>
    <h2>Ur numbers</h2>
    <p id="numbers">123456</p>
    <form action="server_connecter.php" method="post">
        <label for="guess">Your guess:</label>
        <input type="radio" name="iguess" value="doubt">Doubt
        <input type="radio" name="iguess" value="equal">Equal
        <input type="radio" name="iguess">Number
        <input type="text" id="guess" name="guess"><br><br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>