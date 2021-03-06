<?php
setcookie("theme", "1", time() + 86400, "/");
setcookie("width", "1", time() + 86400, "/");
?>
<html>
<head>
    <link id="theme" rel="stylesheet" href="main_theme.css">
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
        #multi{
            cursor: pointer;
        }
        #single{
            padding: 10px;
            border-radius: 100px;
            background-color: var(--inputbgcolor);
            color: black;
            border: none;
            margin: 0 0 0 5px;
            display: inline;
            cursor: pointer;
            transition: background-color 0.4s;
        }
        #single:hover{
            background-color: var(--submitbgcolorhover);
        }
    </style>
</head>
<body>
    <select id="themeSelect" class="dropdown" onchange="ThemeSelector()">
        <option id="dark" value="1" selected="selected">Dark theme</option>
        <option id="light" value="2">Light theme</option>
        <option id="custom" value="3">custom theme</option>
    </select>
    <div style="position: relative; left: 10em; top: 1.05em; width: 25%;">
        <button id="customize" onclick="document.location='customize.php'" style="display: none; padding: 4px; border-radius: 7.5px">Change</button>
    </div>
    <h2>Type in your username!</h2>
    
    <form id="form" action="multiplayer/hub.php" method="get">
        <div style="width: auto;">
            <input type="text" id="username" name="username" style="width: 220px;"><br><br>
            <div>
                <input id="multi" type="submit" value="Multi Player">
                <div id="single" onclick="document.location='singleplayer/perudo.html?username=' + document.getElementById('username').value ">Single player</div>
            </div>
        </div>
    </form>
<script type="text/javascript">
    themeSetup(getCookie("theme"));
    /*console.log(document.getElementById("multi").offsetWidth);
    console.log(document.getElementById("single").offsetWidth);
    console.log(document.getElementById("single").style.marginLeft);*/
    var a = document.getElementById("multi").offsetWidth + document.getElementById("single").offsetWidth + 9;
    //console.log(a);
    document.getElementById("username").style = "width: " + a + "px;";
    switch(getCookie("theme")){
        case 1:
            document.getElementById("dark").selected = "selected";
            break;
        case 2:
            document.getElementById("light").selected = "selected";
            break;
        case 3:
            document.getElementById("custom").selected = "selected";
            break;
    }
    function ThemeSelector(){
        var themeId = document.getElementById("themeSelect").value;
        if(themeId == 3){
            if(getCookie("bgc") == ""){
                location.href = 'themeSetup.html';
            }
            document.getElementById("customize").style = "display: inline-block; padding: 4px; border-radius: 7.5px";
        }
        else{
            document.getElementById("customize").style = "display: none; padding: 4px; border-radius: 7.5px";
        }
        document.cookie = "theme=" + themeId + "; expires=86400000; path=/";
        themeSetup(themeId);
    }
</script>
</body>
</html>