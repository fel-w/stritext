
<?php
    $con=mysqli_connect("localhost","root","","stritext");
    // Check connection
    if (mysqli_connect_errno())
      {
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
      }


    $blacklist = fopen("blacklist.txt", "r");
    while(!feof($blacklist)) { 
        $data = explode(",", fgets($blacklist));

        $user_id=$data[0];
        $job_name = $data[1];
        $time = $data[2];
        $date = $data[3];

        $sql = "INSERT INTO blacklist (sID,jobType,Time,Date) VALUES ('s$user_id','$job_name','$time','$date')";
        $query = mysqli_query($con,$sql);
        
    }
    $blacklist = fopen("blacklist.txt", "w");
    fclose($blacklist);

?>

<?php    
    $del_sql = "DELETE FROM blacklist WHERE sID = 's'";
    $del_query = mysqli_query($con,$del_sql);
?>