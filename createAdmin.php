    
<!DOCTYPE html>
    <html>
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <link rel="stylesheet" href="css/bootstrap.min.css">
            <script src="js/jquery.min.js"></script>
            <script src="js/bootstrap.min.js"></script>
            <style>
                html, body {
                    font-family: 'Roboto', sans-serif;
                    font-size: 100%;
                    overflow-x: hidden;
                    background: url(img/bg.png) no-repeat 0px 0px;
                    background-size: cover;
                }
                h2 {
                    color: white;
                    font-size: 29px;
                    letter-spacing: 2px;
                    text-transform: uppercase;
                    padding-bottom: 15px;
                    text-align: center;   
                }
                log{
                    box-sizing: border-box;
                    display: block;
                }
                .layout{
                    width: 40%;
                    margin: 9em auto;
                    background: rgba(106, 103, 116, 0.37);
                    padding: 42px 35px;
                }
                input.ggg {
                    width: 100%;
                    padding: 25px;
                    border: 1px solid #fff;
                    outline: none;
                    font-size: 18px;
                    color: #fff;
                    background: none;
                    
                }
                .layout input[type="submit"] {
                    padding: 12px 38px;
                    font-size: 18px;
                    text-transform: uppercase;
                    letter-spacing: 2px;
                    background: #a1c3de;
                    color: white;
                    border: none;
                    outline: none;
                    display: table;
                    cursor: pointer;
                    margin: 45px auto 31px;
                }
                .layout input[type="submit"]:hover {
                    background:#244966;
                    transition:0.5s all;
                    -webkit-transition:0.5s all;
                    -o-transition:0.5s all;
                    -moz-transition:0.5s all;
                    -ms-transition:0.5s all;
                }            
            </style>
        </head>
        <body>
        <div class="log">
            <div class="layout">
                <h2>Create Administrator Account</h2>
                    <form action="create.php" method="post">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <input id="username" class="form-control ggg" type="text" name="Username" placeholder="Username" required="">
                        </div><br>
                        <br><div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input id="password" class="form-control ggg" type="password" name="Password" placeholder="Password" required="">
                        </div>        
                            
                            <input type="submit" value="Create Account" name="login">
                    </form>
            </div>
        </div>
        </body>
    </html>
