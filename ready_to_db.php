<?php
    $con=mysqli_connect("localhost","root","","stritext");
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    $ready_file = fopen("ready_jobs.txt", "r");
    
    //Counting the number of lines (jobs) for the sixty seconds when the script runs
    $lines = count(file('ready_jobs.txt'));
    //echo $lines;

    while(!feof($ready_file)) { 
        $data = explode(",", fgets($ready_file));

        
        $user_id = $data[0];
        $job_id = $data[1];
        $job_name = $data[2];
        $time = $data[3];
        $date = $data[4];
        $duration = $data[5];

        $sql = "INSERT INTO logs (sID,jobID,jobType,Time,Date,Duration) VALUES ('s$user_id','$job_id','$job_name','$time','$date','$duration')";
        $query = mysqli_query($con,$sql);

    }
    //permission denied
    $ready_file = fopen("ready_jobs.txt", "w");
    fclose($ready_file);
?>


<?php    
    //deleteing the extra row
    $del_sql = "DELETE FROM logs WHERE sID = 's'";
    $del_query = mysqli_query($con,$del_sql);
?>
