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
    $cycle = $db->querySingle('SELECT cycle FROM "game" WHERE id = 0');
    if($cycle != 2){
        header('Location: waitingForTurn.php');
        die('Game is still ongoing!!!');
    }
    $cpi = $db->querySingle('SELECT cPlayerId FROM "game" WHERE id = 0');
    $winner = $db->querySingle('SELECT name FROM "game" WHERE id = ' . $cpi);
    echo $winner . " is the winner";
    
    /*$db->exec('BEGIN'); <- how to do dis?
    $db->query('DROP TABLE "game"');
    $db->query('DROP TABLE "eventtable"');
    $db->exec('COMMIT');*/
?>
</div>
</body>
</html>