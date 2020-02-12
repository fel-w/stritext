
<?php

    $conn = mysqli_connect("127.0.0.1","root","");   
    if(!$conn){
        die("Connection failed: " .mysqli_connect_error());
    }
    
    $db = mysqli_select_db($conn, "stritext");
    if(!$db){
        die("Error ".mysqli_error($db));   
	}
    
?>