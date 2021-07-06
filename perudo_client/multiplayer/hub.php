<html>
<head>

</head>
<body>
    <script type="text/javascript">
        <?php
            $db = new SQLite3('../databases/hub.sqlite', SQLITE3_OPEN_READONLY);
            $results = $db->query('SELECT id, isdisplay FROM "hub"');
            while($row = $results->fetchArray()){
                $id[] = $row["id"];
                $display[] = $row["isdisplay"];
            }
            $results->finalize();
            $row = null;
            $key = array_search("0", $display);
            while(is_int($key)){
                unset($id[$key]);
                unset($display[$key]);
                $key = array_search("0", $display);
            }
            $key = null;
            //$id = array_values(array_filter($id)); //empties the array for some odd reason....
            $display = null;
            //$display = array_values(array_filter($display));
        ?>
        let id = <?php echo json_encode($id); ?>;
        console.log(id);
        id.unshift("Create new game!");
        console.log(id);
        
        function divgen(){
            if(!Array.isArray(id)){
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
    </script>
</body>
</html>