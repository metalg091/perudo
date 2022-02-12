
<html>
    <head>
    
    </head>
    <body>
        <?php
            setcookie("bgc", $_GET["bgc"], time() + 86400 * 30, "/");
            setcookie("txtc", $_GET["txtc"], time() + 86400 * 30, "/");
            setcookie("inbgc", $_GET["inbgc"], time() + 86400 * 30, "/");
            setcookie("inbgcfocus", $_GET["inbgcfocus"], time() + 86400 * 30, "/");
            setcookie("buttonbgchover", $_GET["buttonbgchover"], time() + 86400 * 30, "/");
            setcookie("eventbgc", $_GET["eventbgc"], time() + 86400 * 30, "/");
            setcookie("theme", "3", time()+ 86400 * 30, "/");
            if(isset($_GET["upload"]) && $_GET["upload"] == "on"){
                $db = new SQLite3('databases/theme.sqlite', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
                $db->query('CREATE TABLE IF NOT EXISTS "theme" (
                    "id" INTEGER UNIQUE PRIMARY KEY AUTOINCREMENT NOT NULL,
                    "name" TEXT UNIQUE NOT NULL,
                    "bgc" TEXT,
                    "txtc" TEXT,
                    "inbgc" TEXT,
                    "inbgcfocus" TEXT,
                    "buttonbghover" TEXT,
                    "eventbgc" TEXT)');
                try{
                    $_GET["themename"] = str_replace('"', '\"', $_GET["themename"]);
                    $db->enableExceptions(true);
                    $db->exec('BEGIN');
                    $db->query('INSERT INTO theme ("name", "bgc", "txtc", "inbgc", "inbgcfocus", "buttonbghover", "eventbgc") VALUES ("' . $_GET["themename"] . '", "' . $_GET["bgc"] . '", "' . $_GET["txtc"] . '", "' . $_GET["inbgc"] . '", "' . $_GET["inbgcfocus"] . '", "' . $_GET["buttonbgchover"] . '", "' . $_GET["eventbgc"] . '")');
                    $db->exec('COMMIT');
                }
                catch(Exception $e){
                    echo "theme name is already occupied!!!";
                    echo '<br><a href="themeSetup.html">Retry</a>';
                    die();
                }
            }
            header('Location: index.php')
        ?>
    </body>
</html>