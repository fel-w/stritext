    

            <?php
                $conn = mysqli_connect("localhost","root","","stritext");
                if(!$conn){
                    die("Connection failed: " .mysqli_connect_error());
                }
                
                $uname = $_POST['Username'];  
                $pass = md5($_POST['Password']);
                
                $sql = "INSERT INTO administrator (Username,Password)VALUES ('$uname','$pass')";
                if (mysqli_query($conn, $sql)) {
                        echo "Successful";
                        header("refresh:1;url=index.php");
                }else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                }
                
                mysqli_close($conn);
            ?>
      
