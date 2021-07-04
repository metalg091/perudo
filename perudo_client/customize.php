<html>
    <head>
    <style>
        body{
            background-color: #0073e6;
        }
        .row{
            border: 2px solid black;
            border-radius: 100px;
            border-collapse: collapse;
            padding: 10px;
            margin-top: 0.5%;
            background-color: rgb(255, 255, 255, 0.3);
            color: black;
            text-align: center;
        }
        .row:hover{
            background-color: rgb(255, 255, 255, 0.5);
        }
    </style>
    </head>
    <body>
    <div id="themes">

    </div>
    <script type="text/javascript">
        <?php
            $db = new SQLite3('databases/theme.sqlite', SQLITE3_OPEN_READONLY);
            /*$db->query('CREATE TABLE IF NOT EXISTS "theme" (
                "id" INTEGER UNIQUE PRIMARY KEY AUTOINCREMENT NOT NULL,
                "name" TEXT UNIQUE NOT NULL,
                "bgc" TEXT,
                "txtc" TEXT,
                "inbgc" TEXT,
                "inbgcfocus" TEXT,
                "buttonbghover" TEXT,
                "eventbgc" TEXT)');*/
            $results = $db->query('SELECT * FROM "theme"');
           /* $name = Array();
            $bgc = Array();
            $txtc = Array();
            $inbgc = Array();
            $inbgcfocus = Array();
            $buttonbghover = Array();
            $eventbgc = Array();*/
            while($row = $results->fetchArray()){
                $name[] = $row["name"];
                $bgc[] = $row["bgc"];
                $txtc[] = $row["txtc"];
                $inbgc[] = $row["inbgc"];
                $inbgcfocus[] = $row["inbgcfocus"];
                $buttonbghover[] = $row["buttonbghover"];
                $eventbgc[] = $row["eventbgc"];
            }
            $results->finalize();
            $row = null;
            $db-> close();
            /*echo json_encode($name);
            echo json_encode($bgc);
            echo json_encode($txtc);
            echo json_encode($inbgc);
            echo json_encode($inbgcfocus);
            echo json_encode($buttonbghover);
            echo json_encode($eventbgc);*/
        ?>
        let name = <?php echo json_encode($name); ?>;
        document.getElementById("themes").appendChild(divgen());
        function arraymaker(newarray){ //makes array from php string output
            newarray = newarray.replace("[", "");
            newarray = newarray.replace("]", "");
            newarray = newarray.replaceAll('"', "");
            newarray = newarray.split(",");
            return newarray;
        }
        function divgen(){
            if(!Array.isArray(name)){
                nametemp = name;
                name = null;
                name = Array(nametemp);
                nametemp = null;
                console.log("only one element");
            }
            var maindiv = document.createElement("div");
            maindiv.setAttribute("id", "mdiv");
            for(let i = 0; i < name.length; i++){
                var div = document.createElement("div");
                div.setAttribute("class", "row");
                div.setAttribute("onclick", "Select(this.id)");
                div.setAttribute("id", i);
                var textname = document.createTextNode(name[i]);
                div.appendChild(textname);
                maindiv.appendChild(div);
            }
            return maindiv;
        }
        function Select(id){
            const bgc = arraymaker('<?php echo json_encode($bgc); ?>');
            const txtc = arraymaker('<?php echo json_encode($txtc); ?>');
            const inbgc = arraymaker('<?php echo json_encode($inbgc); ?>');
            const inbgcfocus = arraymaker('<?php echo json_encode($inbgcfocus); ?>');
            const buttonbghover = arraymaker('<?php echo json_encode($buttonbghover); ?>');
            const eventbgc = arraymaker('<?php echo json_encode($eventbgc); ?>');
            location.href = 'customThemeSaver.php?bgc=' + encodeURIComponent(bgc[id]) + '&txtc='+ encodeURIComponent(txtc[id]) + '&inbgc=' + encodeURIComponent(inbgc[id]) + '&inbgcfocus=' + encodeURIComponent(inbgcfocus[id]) + '&buttonbgchover=' + encodeURIComponent(buttonbghover[id]) + '&eventbgc=' + encodeURIComponent(eventbgc[id]);
        }
        </script>
    </body>
</html>