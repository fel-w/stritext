<?php
    $con=mysqli_connect("localhost","root","","stritext");
    // Check connection
    if (mysqli_connect_errno())
      {
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
      }


    $busylist = fopen("busy_list.txt", "r");
    while(!feof($busylist)) { 
        $data = explode(" ", fgets($busylist));

        $user_id=$data[0];
        $job_name = $data[1];
        $time = $data[5];
        $date = $data[6];

        $sql = "INSERT INTO waiting (sID,jobType,Time,Date) VALUES ('s$user_id','$job_name','$time','$date')";
        $query = mysqli_query($con,$sql);
        
    }
    $busylist = fopen("busy_list.txt", "w");
    fclose($busylist);

?>
<?php    
    $del_sql = "DELETE FROM waiting WHERE sID = 's'";
    $del_query = mysqli_query($con,$del_sql);
?>