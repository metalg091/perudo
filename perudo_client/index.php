<?php
setcookie("theme", "../dark_theme.css", time() + 86400, "/");
?>
<html>
<head>
    <link id="theme" rel="stylesheet" href="<?php echo $_COOKIE["theme"]?>">
    <link rel="stylesheet" href="button.css">
    <script src="themeSwitch.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    
    <form id="form" action="multiplayer/getUserName.php" method="get">
        <input type="checkbox" id="height" name="height" checked style="display: none;"> 
        <input type="text" id="username" name="username"><br><br>
        <input type="submit" value="Submit">
    </form>
<script type="text/javascript">
    var width = window.innerWidth;
    document.cookie = "width=" + width + "; expires=86400000; path=/";
</script>
</body>
</html>