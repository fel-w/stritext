<?php
session_start();
?>    
<!DOCTYPE html>
    <html lang="en">
        <head>
            <title>Login</title>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <link rel="stylesheet" href="css/bootstrap.min.css">
            <script src="js/jquery.min.js"></script>
            <script src="js/bootstrap.min.js"></script>
            <style>
                body {
                    font-family: 'Verdana', sans-serif;
                    background: url(img/stxt.png);
                    background-repeat: repeat-y;
                    background-size: cover;
                    background-size: 104%;
                    color: white;
                }
                .logArea{
                    box-sizing: border-box;
                    background: rgba(29, 28, 29, 0.9);
                    width:45%;
                    margin-left: 380px;
                    margin-top: 120px;
                    padding: 20px;
                }
                .image{
                    text-align: center;
                }
                .username input, .password input{
                    
                    text-indent: 10px; 
                    padding: 5px;
                    border: none;
                    border-radius: 8px;
                    outline: none;
                    font-size: 18px;
                    color: #ffffff;
                    background: #6F6C6D;
                    width: 95%;
                }
                input[type="submit"]{
                    text-transform: uppercase;
                    background:#244966;
                    color: white;
                    border: none;
                    outline: none;
                    cursor: pointer;
                    padding: 10px;
                    width: 20%;
                    border-radius: 4px;
                    font-weight: bold;
                }
                input[type="submit"]:hover{
                    width: 22%;
                    background: #5c768a;
                    transition:0.4s all;
                    -webkit-transition:0.4s all;
                    -o-transition:0.4s all;
                    -moz-transition:0.4s all;
                    -ms-transition:0.4s all;
                }
                input[type="submit"]:active{
                    background:#244966;
                }
                .row{
                    margin-left: 10px;
                    margin-top: 20px;
                }
                .row p{
                    font-size: 18px;
                }
            
                @media (max-width: 767px){
                    input[type="submit"]{
                        background: #5c768a;
                        transition:0.4s all;
                        -webkit-transition:0.4s all;
                        -o-transition:0.4s all;
                        -moz-transition:0.4s all;
                        -ms-transition:0.4s all;
                    }
                    .username input, .password input{
                        text-indent: 5px; 
                        padding: 3px;
                        font-size: 13px;
                    }
                    h3{
                        font-size: 18px;
                    }
                    .row p{
                    font-size: 14px;
                    }
                    .logArea{
                        margin-left: 190px;
                        margin-top: 130px;
                        padding: 20px;
                    }	
                }
            </style>
        </head>
        <body>
            <div class="container logArea">
                <div class= "image">
                    <img src="img/user.png" alt="User Icon">
                    <img src="img/strit_logo.png" alt="Stritext Logo image"><br>
                    <h3>Administrator Login</h3>
                </div>
                <form action="slogin.php" method="post">
                    <div class="row">
                        <div class="col-sm-6 username">
                            <p>Username:</p>
                            <input type="text" name="Username" maxlength="15" required> 
                        </div>
                        <div class="col-sm-6 password">
                            <p>Password:</p>
                            <input type="password" name="Password" maxlength="30" required>
                        </div>
                    </div><br>
                    <div class="text-right">
                        <input type="submit" value="Go">
                    </div>                
                </form>
            </div>
        </body>
    </html>
<?php
session_destroy();
?>