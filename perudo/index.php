<html>
<head>
    <link rel="stylesheet" href="dark_theme.css">
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
    <h2>Type in your username, or if u have dropped out of the game, enter your ID!</h2>
    
    <form action="getUserName.php" method="get">
        <input type="text" id="username" name="username"><br><br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>