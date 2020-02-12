
<?php
    session_start();


    $conn = mysqli_connect("127.0.0.1","root","");   
    if(!$conn){
        die("Connection failed: " .mysqli_connect_error());
    }
    
    $db = mysqli_select_db($conn, "stritext");
    if(!$db){
        die("Error ".mysqli_error($db));   
	}

    $uname = $_POST['Username'];
    $pass = md5($_POST['Password']);
    
    $query = mysqli_query($conn,"SELECT * FROM administrator WHERE Username='$uname' and Password='$pass'");
    $count=mysqli_num_rows($query);
    if($count==1){
        echo "Loading...";
        $_SESSION['log']=1;
        header("refresh:1;url=portal.php");
    
    }
    else{
        echo "Incorrect Credentials, check and try again";
        header("refresh:2;url=index.php");
    }
    
?>


                    