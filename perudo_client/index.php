<?php
setcookie("theme", "1", time() + 86400, "/");
setcookie("width", "1", time() + 86400, "/");
?>
<html>
<head>
    <link id="theme" rel="stylesheet" href="../dark_theme.css">
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
<!--label class="switch">
    <input class="toggle-state" type="checkbox" name="check" value="check" onchange="themeSwitch(theme)"/><div></div>
</label-->
    <select id="themeSelect" onchange="ThemeSelector()">
        <option id="dark" value="1" selected="selected">Dark theme</option>
        <option id="light" value="2">Light theme</option>
        <option id="custom" value="3">custom theme</option>
    </select>
    <h2>Type in your username!</h2>
    
    <form id="form" action="multiplayer/getUserName.php" method="get">
        <input type="checkbox" id="height" name="height" checked style="display: none;"> 
        <input type="text" id="username" name="username"><br><br>
        <input type="submit" value="Submit">
    </form>
<script type="text/javascript">
    <?php
        echo "themeSetup(" . $_COOKIE["theme"] . ");";
    ?>
    
    var width = window.innerWidth;
    document.cookie = "width=" + width + "; expires=86400000; path=/";
    
    /*switch(getCookie("theme")){
        case 1:
            document.getElementById("dark").selected = "selected";
            break;
        case 2:
            document.getElementById("light").selected = "selected";
            break;
        case 3:
            document.getElementById("custom").selected = "selected";
            break;
    }*/

    function ThemeSelector(){
        var themeId = document.getElementById("themeSelect").value;
        if(themeId == 3){
            if(getCookie("bgc") == ""){
                location.href = 'themeSetup.html';
            }
        }
        document.cookie = "theme=" + themeId + "; expires=86400000; path=/";
        themeSetup(themeId);
    }
</script>
</body>
</html>
