
<?php
    ob_start ();
    error_reporting (0);
    $conn_chart = mysqli_connect("127.0.0.1","root","");   
    if(!$conn_chart){
        die("Connection failed: " .mysqli_connect_error());
    }
    
    $db = mysqli_select_db($conn_chart, "stritext");
    if(!$db){
        die("Error ".mysqli_error($db));   
	}
    $result_chart = array();
    $jobname = array ("encrypt","double","reverse","delete","decrypt","replace");
    for($x = 0; $x < 6; $x++){
        $sql_chart = "SELECT * FROM logs WHERE jobtype = '$jobname[$x]'";
        $query_chart = mysqli_query($conn_chart,$sql_chart);
        $job = $jobname[$x];
        $result_chart[$job] = mysqli_affected_rows($conn_chart);
    }
    $jobvalue = array();
    $jv=0;
    foreach ($result_chart as $c => $c_value){
        //echo "For $c the number is $c_value <br>";
        $jobvalue[$jv] = $c_value;
        //echo "$jobvalue[$jv] <br>";
        $jv++;
        
    }
    
    
?>
