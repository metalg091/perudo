<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body{
            background-color: #0073e6;
        }
        .row{
            border: 2px solid black;
            border-radius: 100px;
            border-collapse: collapse;
            padding: 0.75em;
            margin-top: 10px;
            background-color: rgb(255, 255, 255, 0.3);
            color: black;
            text-align: center;
        }
        .row:hover{
            background-color: rgb(255, 255, 255, 0.5);
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div id="main">
    </div>
    <script type="text/javascript">
        <?php
            $db = new SQLite3('../databases/hub.sqlite', SQLITE3_OPEN_READONLY);
            $results = $db->query('SELECT id, isdisplay FROM "hub" ORDER BY id DESC');
            while($row = $results->fetchArray()){
                $id[] = $row["id"];
                $display[] = $row["isdisplay"];
            }
            $results->finalize();
            $row = null;
            $key = array_search(0, $display);
            while(is_int($key)){
                unset($id[$key]);
                unset($display[$key]);
                $key = array_search(0, $display);
            }
            $key = null;
            $id = array_values(array_filter($id)); //removes id where value (and index?) is 0
            $display = null;
            //$display = array_values(array_filter($display));
            function NewRoomNumber($id, $start = 0, $end = "a"){
                echo "start: " . $start . " end: " . $end . "<br>";
                /*if($end == "a"){
                    $end = count($id) - 1;
                    echo "start: " . $start . " end: " . $end . "<br>";
                }*/
                if($end - $start == 1){
                    if($id[0] == 1){
                        return $end + 1;
                    }
                    else{
                        return 1;
                    }
                }
                else{
                    $i = round(($end + $start) / 2);
                    if($id[$i] == $i + 1){
                        $start = $i;
                        return NewRoomNumber($id, $start, $end);
                    }
                    else{
                        $end = $i;
                        return NewRoomNumber($id, $start, $end);
                    }
                }
            }
        ?>
        let id = <?php echo json_encode($id); ?>;
        id.unshift("Create new game!");
        document.getElementById("main").appendChild(divgen());
        function divgen(){
            /*if(!Array.isArray(id)){
                nametemp = name;
                name = null;
                name = Array(nametemp);
                nametemp = null;
                console.log("only one element");
            }*/
            var maindiv = document.createElement("div");
            maindiv.setAttribute("id", "mdiv");
            for(let i = 0; i < id.length; i++){
                var div = document.createElement("div");
                div.setAttribute("class", "row");
                div.setAttribute("onclick", "Select(this.id)");
                div.setAttribute("id", i);
                var textname = document.createTextNode(id[i]);
                div.appendChild(textname);
                maindiv.appendChild(div);
            }
            return maindiv;
        }
        function Select(id){
            if(id == 0){
                //create new room here
            }
            else{
                //set sessin room id
            }
        }
        function NewRoomNumber(id, start, end){
            console.log("start: " + start + " end: " + end);
        }
    </script>
</body>
</html>