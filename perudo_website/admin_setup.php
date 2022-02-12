<html>
    <?php
        $db = new SQLite3('databases/hub.sqlite', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
        $db->query('CREATE TABLE IF NOT EXISTS "hub" (
            "id" INTEGER UNIQUE NOT NULL,
            "isdisplay" INTEGER,
            "date" REAL)');
    ?>
</html>