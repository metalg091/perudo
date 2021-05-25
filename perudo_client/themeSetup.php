<html>
<head>
    <link rel="stylesheet" href="dark_theme.css" id="theme">
    <style>
        .colorpick{
            position: absolute;
            bottom: 10px;
            display: inline-block;
        }
    </style>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div id="container">
    <h2>Your name</h2>
    <h3 id="username" class="data">Your username</h3>
    <h2>Other players</h2>
    <div id="others" class="data">
        <table>
            <tr>
                <td>test user 1</td>
                <td>5</td>
            </tr>
            <tr>
                <td>test user 2</td>
                <td>4</td>
            </tr>
            <tr>
                <td>test user 3</td>
                <td>3</td>
            </tr>
            <tr>
                <td>test user 4</td>
                <td>2</td>
            </tr>
            <tr>
                <td>test user 5</td>
                <td>1</td>
            </tr>
        </table>
    </div>
    <h2>Cubes in game</h2>
    <p id="allcubes" class="data">15</p>
    <h2>Your numbers</h2>
    <p id="urnumbers" class="data">12345</p>
    <form action="" method="">
        <label for="guess">Your guess:</label>
        <div id="raddiv" style="margin: 20px; display: inline;">
        <div id="rad1">
            <input id="radio1" type="radio" name="iguess" value="1">Doubt
        </div>
        <div id="rad2">
            <input id="radio2" type="radio" name="iguess" value="2">Equal
        </div>
        <div id="rad3">
            <input id="radio3" type="radio" name="iguess" value="3" checked>Number:
        </div>
        </div>
        <input type="text" value="2" id="guess" name="guess1">
        <input type="number" value="5" id="guess1" name="guess2" min="1" max="6"><br><br>
        <input id="submit" type="submit" value="Submit" style="display: none">
    </form>
    </div>
    <h2 id="iframeTitle">Events:</h2>
    <iframe srcdoc="
    <html>
        <head>
            <link rel='stylesheet' href='dark_theme.css'>
        </head>
        <body id='event'>
        <table>
            <tr>
                <td>test user 1</td>
                <td>guessed</td>
                <td>32</td>
            </tr>
            <tr>
                <td>test user 2</td>
                <td>guessed</td>
                <td>42</td>
            </tr>
            <tr>
                <td>test user 3</td>
                <td>doubted the </td>
                <td>42</td>
                <td>from test user 2</td>
            </tr>
            <tr>
                <td>tset user 2</td>
                <td>lost a Cube</td>
            </tr>
        </table>
        </body>
        </html>" id="eventGetter">
    </iframe>
    <label for="" class="colorpick">background color</label>
    <input style="left: 130px;" type="color" id="backgroundColor" value="#fff" class="colorpick">
    <script defer type="text/javascript">
        function setInputFilter(textbox, inputFilter) { //only allows numeric input
            ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function(event) {
                textbox.addEventListener(event, function() {
                    if (inputFilter(this.value)) {
                        this.oldValue = this.value;
                        this.oldSelectionStart = this.selectionStart;
                        this.oldSelectionEnd = this.selectionEnd;
                    }
                    else if (this.hasOwnProperty("oldValue")) {
                        this.value = this.oldValue;
                        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
                    }
                    else {
                        this.value = "";
                    }
                });
            });
        }

        setInputFilter(document.getElementById("guess"), function(value) {
        return /^\d*$/.test(value); });
        setInputFilter(document.getElementById("guess1"), function(value) {
        return /^\d*$/.test(value); });
    </script>
</body>
</html>