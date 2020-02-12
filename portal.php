<?php
    session_start();
    if(isset($_SESSION['log']))
    {

        include "high.php";
        include "fail.php";
        include "chart.php";
        include "ready_to_db.php";//file for taking jobs to database
        include "blacklist_to_db.php";//file for taking blacklisted jobs to database
        include "busylist_to_db.php";//file for taking jobs to database
    
        
        $count_hour = 0;
        $count_day = 0;
        $temp_sixty = 0;
        $temp_hour = 0;
        $average_sixty  = 0;
        $average_hour = 0;
        $average_day = 0;
                
        $sql_total = "SELECT * FROM logs";
        $sql_total2 = "SELECT * FROM waiting";
        $sql_blacklist = "SELECT * FROM blacklist";
        $query_total = mysqli_query($conn,$sql_total);
        $query_total2 = mysqli_query($conn,$sql_total2);
        $query_blacklist = mysqli_query($conn,$sql_blacklist);
        $totalRows = (mysqli_num_rows($query_total) + mysqli_num_rows($query_total2));
        $totalBlacklist = (mysqli_num_rows($query_blacklist));
        $percentBlacklist = round(($totalBlacklist/($totalRows+$totalBlacklist)*100),2);
        
        if ($lines !=null){
            $average_sixty = round(($lines/60),2);
            $temp_sixty += $average_sixty; 
            $count_hour += 1; 
        }
        if ($count_hour == 60){
            $average_hour = round(($temp_sixty/60),2);
            $temp_hour += $average_hour;
            $count_day += 1; 
        }
        if ($count_day == 24){
            $average_day = round(($temp_hour/24),2);
        }
?>

<!DOCTYPE html>
    <html lang="en">
        <head>
            <title>Stritext Administrator</title>
            <meta charset="utf-8">
            <meta http-equiv="refresh" content="60">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            
            <link rel="stylesheet" href="css/bootstrap.min.css">
            
            <link rel="stylesheet" href="css/portalStyle.css">
            
            <script src="js/jquery.min.js"></script>
            
            <script src="js/bootstrap.min.js"></script>
            <style>
                #main{
                    font-family: 'Verdana', sans-serif;
                    background: url(img/stxt2.png);
                    background-repeat: repeat-y;
                    background-size: cover;
                    background-size: 104%;
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
                        <a class="navbar-brand" href="portal.php" style="font-size:24px;">Stritext</a>
                    </div><!--end of header-->
                    <div class="collapse navbar-collapse" id="logout">
                        <ul class="nav navbar-nav navbar-right">
                            <li style="font-size: 18px;"><a href="index.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
                        </ul><!--end of logout link under toggle button-->
                    </div><!--end of toggle button content-->
                </div><!--end of navigator container-->
            </nav><!--end of nav-->
            
            <!--Body of the site -->
            <div class="container-fluid" id="main">
                <div class="col-sm-12 flexWelcome">
                    <div class="col-sm-2 flex-item-welcome text-center" style="border-radius: 0px;">
                        <p class="itemheading" style="margin-top: 50px; color: #9D9D8A;">Hello &nbsp;&nbsp;<span style="font-size: 18px;"class="glyphicon glyphicon-briefcase"></span> Administrator</p>
                        <p class="welc" >Welcome!</p>
                    </div><!--End of highest success column-->
                
                    <a class="col-sm-4 flex-item-welcome text-center" style="margin: 0 12px 0 12px;" href="currentTasks.php"><!-- tasksToday.php Link -->
                        <span class="glyphicon glyphicon-tasks"></span><br>
                        <span class="itemheading-link-tasks">Currently Accepted Tasks<br><span class="figure-link-tasks"><?php echo $totalRows;?></span></span>
                    </a><!--End of highest failure column-->
                
                    <a class="col-sm-4 flex-item-welcome" href="blacklisted.php"><!-- blacklisted.php Link -->
                        <p class="itemheading-link" >Currently Blacklisted Tasks</p>    
                        <div class="progress-section">
                            <div class="col-sm-5">
                                <p class="figure-link"><?php echo $totalBlacklist;?></p>
                            </div>
                            <div class="progress-bar1" data-percent="<?php echo $percentBlacklist; ?>" data-duration="1000" data-color="#737373,#1D1D1D" style="margin-left: 195px;"></div>
                        </div>
                    </a><!--End of waiting jobs column-->
                </div><!--End of column for row for flexWelcome-->
                
                <!--Row for Average Rate -->
                <div class="col-sm-3 flexRate">
                    <div class="col-sm-12 flex-item-rate">
                        <p class="itemheading">Average Rate for 60 seconds (jobs every second)</p>
                        <p class="figure" style="margin-top: -10px;"><?php echo $average_sixty;?></p>
                    </div><!--end of 30 seconds rate-->
                        
                        
                    <div class="col-sm-12 flex-item-rate">
                        <p class="itemheading">Average Rate for the Hour</p>
                        <p class="figure"><?php echo $average_hour;?></p>
                    </div><!--end of hourly rate-->
                        
                        
                    <div class="col-sm-12 flex-item-rate">
                        <p class="itemheading">Average Rate for the Day</p>
                        <p class="figure"><?php echo $average_day;?></p>
                    </div><!--end of daily rate-->
                </div><!--end of Rate column-->
                
                <div class="col-sm-9 averageGraph">
                    <p class="itemheading">Graphical Illustration</p>
                    <br><br>
                    <canvas id="chart"></canvas>
                </div><!--end of graph column-->
                <!--End of Average Rate Row -->
                
                <div class="col-sm-12 thirdFlex">
                    <a class="col-sm-2 flex-item-3 text-center" href="ready.php"><!--ready.php link-->    
                        <div class="icon">
                            <span class="glyphicon glyphicon-saved glyphStyle"></span>
                            <p class="itemheading-link">Ready Jobs</p>
                        </div>
                    </a><!--End of ready jobs column-->
                    
                    <a class="col-sm-2 flex-item-3 text-center" href="waiting.php"><!--waiting.php link-->
                        <div class="icon">
                            <span class="glyphicon glyphicon-hourglass glyphStyle"></span>
                            <p class="itemheading-link">Waiting Jobs</p>
                        </div>
                    </a><!--End of waiting jobs column-->
                    
                    <a class="col-sm-2 flex-item-3" href="success.php"><!--success.php link-->
                        <p class="itemheading-link">Student with Highest Task Success</p>
                        <div class="progress-section">
                            <div class="col-sm-6">
                                <p class="figure-link"><?php echo $final;?></p>
                            </div>
                            <div class="col-sm-6">
                                <div class="progress-bar1" data-percent="<?php echo $maxPercentage;?>" data-duration="1000" data-color="#bbddbc,#3C763D"></div>
                            </div>
                        </div>
                    </a><!--End of highest success column-->
                    
                    <a class="col-sm-2 flex-item-3" href="failure.php"><!--failure.php link-->
                        <p class="itemheading-link">Student with Highest Task Failure</p>
                        <div class="progress-section">
                            <div class="col-sm-6">
                                <p class="figure-link"><?php echo $final_F;?></p>
                            </div>
                            <div class="col-sm-6">
                                <div class="progress-bar1" data-percent="<?php echo $maxPercentage_F;?>" data-duration="1000" data-color="#e3b6b5,#B04442"></div>
                            </div>
                        </div>
                    </a><!--End of highest failure column-->                    
                </div><!--End of column for thirdFlex row-->
                
            </div><!--end of main container-->
            
            <footer class="container-fluid text-center">
                <img src="img/logo.png" alt="logo" height= "70">
            </footer>
        
        
            <script src = "js/Chart.bundle.min.js"></script>
            <script src="js/jQuery-plugin-progressbar.js"></script>
            <script>
                $(".progress-bar1").loading();                 
            </script>
            
            <script>
                
                var ctx = document.getElementById("chart");
                var data ={
                    datasets: [{
                        label: 'Number of Performed Tasks',
                        data: [<?php echo $jobvalue[0];?>,<?php echo $jobvalue[1];?>,<?php echo $jobvalue[2];?>,<?php echo $jobvalue[3];?>,
                                <?php echo $jobvalue[4];?>,<?php echo $jobvalue[5];?>],
                        backgroundColor: [
                            'rgba(29, 28, 29,0.3)',
                            'rgba(29, 28, 29,0.3)',
                            'rgba(29, 28, 29,0.3)',
                            'rgba(29, 28, 29,0.3)',
                            'rgba(29, 28, 29,0.3)',
                            'rgba(29, 28, 29,0.3)'
                        ],
                        borderColor: [
                            'rgba(30, 76, 104,1)',
                            'rgba(30, 76, 104,1)',
                            'rgba(30, 76, 104,1)',
                            'rgba(30, 76, 104,1)',
                            'rgba(30, 76, 104,1)',
                            'rgba(30, 76, 104,1)',
                            'rgba(30, 76, 104,1)'
                        ],
                        borderWidth: 2
                    }],
                    labels: ["Encrypt","Double","Reverse","Delete","Decrypt","Replace"]
                };
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data:data,
                    options: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: {
                                fontColor: '#1E4C68',
                           }
                        },
                        tooltips: {
                            mode: 'point'
                        },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }],
                            xAxes: [{
                                ticks: {
                                    autoskip: true,
                                    maxTicksLimit:6
                                }
                            }]
                        }
                    }
                  });
            </script>
        
        </body>
    </html>



<?php
    }
    else{
        echo "User not logged in; Please First Login";
        header("refresh:2;url=index.php");
    }
?>