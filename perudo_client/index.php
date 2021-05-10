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
    <h2>Type in your username!</h2>
    
    <form action="getUserName.php" method="get">
        <input type="text" id="username" name="username"><br><br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>