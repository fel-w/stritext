
<?php

    $conn = mysqli_connect("127.0.0.1","root","");   
    if(!$conn){
        die("Connection failed: " .mysqli_connect_error());
    }
    
    $db = mysqli_select_db($conn, "stritext");
    if(!$db){
        die("Error ".mysqli_error($db));   
	}
    
    $sql_F = "SELECT * FROM logs";
    $query_F = mysqli_query($conn,$sql_F);
    $completeArray_F = array();
    while ($row_F = mysqli_fetch_assoc($query_F)){
        $id_F = $row_F["sID"]; 
        $count_sql_F = "SELECT COUNT(sID) FROM logs WHERE sID = '$id_F'";
        $count_query_F = mysqli_query($conn,$count_sql_F);
        $result_F = mysqli_fetch_row($count_query_F); //Result of the number of rows 
        $completeArray_F[$id_F] = $result_F[0]; //The number of rows is stored in an assoc array corresponding to the it's sID
         
    }
    
    $sql_F2 = "SELECT * FROM waiting";
    $query_F2 = mysqli_query($conn,$sql_F2);
    $waitingArray_F = array();
    while ($row_F2 = mysqli_fetch_assoc($query_F2)){
        $id_F2 = $row_F2["sID"];
        $count_sql_F2 = "SELECT COUNT(sID) FROM waiting WHERE sID = '$id_F2'";
        $count_query_F2 = mysqli_query($conn,$count_sql_F2);
        $result_F2 = mysqli_fetch_row($count_query_F2);
        $waitingArray_F[$id_F2] = $result_F2[0];
    }
 
    //determine the failure percentage for each job
    $percentArray_F = array();// array to store percentage of each job; 
    
    // get the value in the first array
    foreach ($waitingArray_F as $x => $x_value){
        // determine if a sID from the logs table exists in the waiting table
        if(array_key_exists($x,$completeArray_F)){
            $percentArray_F[$x] = round(($x_value/($x_value+$completeArray_F[$x])*100),1);
        }else{
            $percentArray_F[$x] = round(($x_value/($x_value+0)*100),1);
        }
    }
    $maxPercentage_F =0; //variable to store highest percentage
    
    $highestFailure = array(); //array to store the sID's with the value of the highest success
    foreach ($percentArray_F as $z => $z_value){
        $maxPercentage_F = max($maxPercentage_F,$z_value);
    }
    
    foreach ($percentArray_F as $z => $z_value){
        foreach ($waitingArray_F as $x => $x_value){    
            if ($z_value == $maxPercentage_F && $z == $x){
                $highestFailure[$z] = $waitingArray_F[$x];
            }
        }
    }
   
    $no_Occurrences_F = 0; //variable to store the number of occurrences of an sID with the highest percentage in the completed table (logs)
    foreach ($highestFailure as $a => $a_value){
        $no_Occurrences_F = max($no_Occurrences_F,$a_value);
    }
    
    $final_F = array_search($no_Occurrences_F,$highestFailure);
    
    if ($final_F == NULL){
        $final_F = 0;
    }
?>
