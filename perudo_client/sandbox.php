<html>
    <head>
        <script src="sandbox.js" type="text/javascript"></script>
</head>
<body>
    <?php
        $length = 1000;
        $array = arraygen($length, 1, $length);
        $a = NewRoomNumber($array, 0, count($array) - 1);
        echo $a;
        function arraygen($length, $min, $max){
            $result = Array();
            for($i = 0; $i < $length; $i++){
                $result[] = rand($min, $max);
            }
            $result = array_unique($result);
            $result = array_values(array_filter($result));
            sort($result);
            print_r($result);
            echo "<br>";
            return $result;
        }
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
</body>
</html>