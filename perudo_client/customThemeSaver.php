
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
            setcookie("theme", "3", time()+ 86400 * 30, "/")
        ?>
        <script>
            location.href = "index.php";
        </script>
    </body>
</html>