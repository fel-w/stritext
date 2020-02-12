<?php
    session_start();
    if(isset($_SESSION['log']))
    {
        include "connect.php";


?>
<!DOCTYPE html>
    <html lang="en">
        <head>
            <title>Tasks Currently</title>
            <meta charset="utf-8">
            <meta http-equiv="refresh" content="60">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            
            <link rel="stylesheet" href="css/bootstrap.min.css">
            
            <link rel="stylesheet" href="css/cssLinks.css">
            
            <script src="js/jquery.min.js"></script>
            
            <script src="js/bootstrap.min.js"></script>    
            
            <style>
                
                .alert{
                    font-size: 20px;
                    width: 105%;
                }
                th{
                    background-color: #288cbd;
                    color: white;
                }
                @media screen and (max-width: 767px) {
                    .alert{
                        width: 100%;
                        text-align: center;
                    }
                }    
            </style>
            
        </head>
        
        <body>
            <nav class="navbar navbar-fixed-top navbar-inverse" >
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#logout">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button><!--end of toggle button-->
                        <a class="navbar-brand" href="portal.php" style="font-size:24px;">Stritext &nbsp;<span class="glyphicon glyphicon-home"></a>
                    </div><!--end of -->
                    <div class="collapse navbar-collapse" id="logout">
                        <ul class="nav navbar-nav navbar-right">
                            <li style="font-size: 18px;"><a href="index.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
                        </ul><!--end of logout link under toggle button-->
                    </div><!--end of toggle button content-->
                </div><!--end of navigator container-->
            </nav><!--end of nav-->
            
            <!-- Side bar-->
            <div class="container-fluid" style="padding-top: 70px;">
                <div class="row content">
                    <div class="col-sm-3 text-center sidenav" >
                        <ul class="nav nav-pills nav-stacked">
                            <li class="active" style="margin-top: 5px;"><a>Tasks Currently <span class="glyphicon glyphicon-tasks"></span></a></li>
                            <li class="pill-link"><a href="blacklisted.php">Blacklisted Tasks Currently <span class="glyphicon glyphicon-trash"></span></a></li>
                            <li class="pill-link"><a href="ready.php">Ready Jobs <span class="glyphicon glyphicon-saved"></span></a></li>
                            <li class="pill-link"><a href="waiting.php">Waiting Jobs <span class="glyphicon glyphicon-hourglass"></span></a></li>
                            <li class="pill-link"><a href="success.php">Most Successful Student <span class="glyphicon glyphicon-ok"></span></a></li>
                            <li class="pill-link" id= "last-link"><a href="failure.php">Highest Failed Student <span class="glyphicon glyphicon-ban-circle"></span></a></li>
                        </ul>
                    </div><!--end of Side bar-->
                    <div class="col-sm-9 body-content">
                        <div class=" alert alert-info">
                            <p><span class="glyphicon glyphicon-info-sign" style="font-size: 15px;"></span>&nbsp; The table shows recently received tasks</p>
                        </div>
                        <table>
                            <tr>
                                <th>sID </th>
                                <th>jobID</th>
                                <th>jobType</th>
                                <th>Time</th>
                                <th>Date</th> 
                                <th>Status</th>
                                <th>Duration</th>
                            </tr>
                        <?php
                            $sql_c1 = "SELECT * FROM logs";
                            $sql_c2 = "SELECT * FROM waiting";
                            $query_c1 = mysqli_query($conn,$sql_c1);
                            $query_c2 = mysqli_query($conn,$sql_c2);
                            while ($row_c1 = mysqli_fetch_assoc($query_c1)){
                        ?>        
                            <tr>
                                <td><?php echo $row_c1["sID"];?></td>
                                <td><?php echo $row_c1["jobID"];?></td>
                                <td><?php echo $row_c1["jobType"];?></td>
                                <td><?php echo $row_c1["Time"];?></td>
                                <td><?php echo $row_c1["Date"];?></td> 
                                <td>Complete</td>
                                <td><?php echo $row_c1["Duration"];?></td>
                            </tr>
                        <?php
                            }while ($row_c2 = mysqli_fetch_assoc($query_c2)){
                        ?>
                            <tr>
                                <td><?php echo $row_c2["sID"];?></td>
                                <td><?php echo $row_c2["jobID"];?></td>
                                <td><?php echo $row_c2["jobType"];?></td>
                                <td><?php echo $row_c2["Time"];?></td>
                                <td><?php echo $row_c2["Date"];?></td> 
                                <td>Waiting</td>
                                <td>0</td>
                            </tr>
                        <?php }?>
                        </table>                                              
                    </div>
                </div>
                
                
            </div>
            <footer class="container-fluid text-center">
                <img src="img/logo.png" alt="logo" height= "70">
            </footer>
        </body>
    </html>

<?php
    }
    else{
        echo "User not logged in; Please First Login";
        header("refresh:2;url=index.php");
    }
?>                