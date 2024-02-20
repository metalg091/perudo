<?php
session_start();
$_SESSION["username"] = $_GET["username"];
$_SESSION["sid"] = $_GET["serverid"];
?>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <?php
        $db = new SQLite3('../databases/hub.sqlite', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
        $db->exec('BEGIN');
        $db->query('INSERT OR IGNORE INTO hub ("id", "isdisplay", "date") VALUES (' . $_SESSION["sid"] . ', 1, ' . time() . ')');
        $db->exec('COMMIT');
        $db->close();
        $db = new SQLite3('../databases/perudo' . $_SESSION["sid"] . '.sqlite', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
        $db->query('CREATE TABLE IF NOT EXISTS "game" (
            "id" INTEGER UNIQUE PRIMARY KEY AUTOINCREMENT NOT NULL,
            "name" TEXT,
            "cubes" INTEGER DEFAULT "5",
            "numbers" INTEGER DEFAULT "12345",
            "cPlayerId" INTEGER DEFAULT null,
            "playersInGame" INTEGER DEFAULT null,
            "cycle" INTEGER DEFAULT null)');
        $db->query('CREATE TABLE IF NOT EXISTS "eventtable" (
            "orders" INTEGER UNIQUE PRIMARY KEY AUTOINCREMENT NOT NULL,
            "ide" INTEGER,
            "guess" TEXT,
            "who" INTEGER DEFAULT "0"
            )');
        $db->exec('BEGIN');
        $db->query('INSERT OR IGNORE INTO "game" ("id", "name", "cubes", "numbers", "cPlayerId", "playersInGame", "cycle") VALUES ("0", "system", "null", "null", "1", "0", "6")');
        $db->exec('COMMIT');
        $cycle = $db->querySingle('SELECT cycle FROM "game" WHERE id = 0');
        if($cycle == 2){
            header('Location: winpage.php');
            die("game end");
        }
        $db->exec('BEGIN');
        $db->query('INSERT INTO "game" ("name", "cubes") VALUES ("' . $_GET["username"] . '", 5)');
        $db->query('UPDATE "game" SET playersInGame = playersInGame + 1 WHERE id = 0');
        $db->query('DROP TABLE IF EXISTS "winners"');
        $db->exec('COMMIT');
        
        $_SESSION["id"] = $db->querySingle('SELECT playersInGame FROM "game" WHERE id = 0');
        echo $_SESSION["id"];
        $db->close();
    ?>
    <h2>Your name</h2>
    <h3 id="username">
    <h2>Other players</h2>
    <div id="others">
        
    </div>
    <h2>Cubes in game</h2>
    <p id="cubes">--</p>
    <h2>Ur numbers</h2>
    <p id="numbers">------</p>
    <h1>Please wait until your are registered!!!! (user registration in progress...)</h1>
    <script defer type="text/javascript">
        location.href = 'lobby.php';
    </script>
</body>
</html>