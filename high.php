
<?php

    $conn = mysqli_connect("127.0.0.1","root","");   
    if(!$conn){
        die("Connection failed: " .mysqli_connect_error());
    }
    
    $db = mysqli_select_db($conn, "stritext");
    if(!$db){
        die("Error ".mysqli_error($db));   
	}
    
    $sql = "SELECT * FROM logs";
    $query = mysqli_query($conn,$sql);
    $completeArray = array();
    while ($row = mysqli_fetch_assoc($query)){
        $id = $row["sID"]; 
        $count_sql = "SELECT COUNT(sID) FROM logs WHERE sID = '$id'";
        $count_query = mysqli_query($conn,$count_sql);
        $result = mysqli_fetch_row($count_query); //Result of the number of rows 
        $completeArray[$id] = $result[0]; //The number of rows is stored in an assoc array corresponding to the it's sID
         
    }
    
    $sql2 = "SELECT * FROM waiting";
    $query2 = mysqli_query($conn,$sql2);
    $waitingArray = array();
    while ($row2 = mysqli_fetch_assoc($query2)){
        $id2 = $row2["sID"];
        $count_sql2 = "SELECT COUNT(sID) FROM waiting WHERE sID = '$id2'";
        $count_query2 = mysqli_query($conn,$count_sql2);
        $result2 = mysqli_fetch_row($count_query2);
        $waitingArray[$id2] = $result2[0];
    }
 
    //determine the success percentage for each job
    $percentArray = array();// array to store percentage of each job; 
    
    // get the value in the first array
    foreach ($completeArray as $x => $x_value){
        // determine if a sID from the logs table exists in the waiting table
        if(array_key_exists($x,$waitingArray)){
            $percentArray[$x] = round(($x_value/($x_value+$waitingArray[$x])*100),1);
        }else{
            $percentArray[$x] = round(($x_value/($x_value+0)*100),1);
        }
    }
    $maxPercentage =0; //variable to store highest percentage
    
    $highestSuccess = array(); //array to store the sID's with the value of the highest success
    foreach ($percentArray as $z => $z_value){
        $maxPercentage = max($maxPercentage,$z_value);
    }
    
    foreach ($percentArray as $z => $z_value){
        foreach ($completeArray as $x => $x_value){    
            if ($z_value == $maxPercentage && $z == $x){
                $highestSuccess[$z] = $completeArray[$x];
            }
        }
    }
   
    $no_Occurrences = 0; //variable to store the number of occurrences of an sID with the highest percentage in the completed table (logs)
    foreach ($highestSuccess as $a => $a_value){
        $no_Occurrences = max($no_Occurrences,$a_value);
    }
    
    //$final is to store the sID corresponding to the highest number of occurences and success 
    $final = array_search($no_Occurrences,$highestSuccess);
    
    if ($final == NULL){
        $final = 0;
    }
?>
