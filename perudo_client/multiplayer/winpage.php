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
    $db = new SQLite3('../databases/perudo.sqlite', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
    $cpi = $db->querySingle('SELECT cPlayerId FROM "game" WHERE id = 0');
    $winner = $db->querySingle('SELECT name FROM "game" WHERE id = ' . $cpi);
    echo $winner . " is the winner";
?>
</div>
</body>
</html>