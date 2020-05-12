<?php
    /*if ($_SERVER['HTTPS'] != "on") {
        $url = "https://". $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        header("Location: $url");
        exit;
    }*/
   //if(session_id() == ''){
        session_start();
  // }

?>
<head>
    <title>DP1 WebExam</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0px;
            color: #4d5c65;
            background-color: silver;
        }
        #header{
            width: 100%;
            height: 20%;
            left: 0px;
            top: 0px;
            position: relative;
            background: silver;
            border: solid darkgrey;
        }
        #header a{
            float: right;
            padding: 5px 5px;
            display: block;
            background-color: #515d63;
            border-right: solid white;
            color: white;
            text-decoration: none;
        }
        #nav{
            position: relative;
            width: 15%;
            height: 85%;
            float: left;
            border-right: solid darkgray;
            background: silver;
        }
        #nav a{
            padding: 5px;
            display: block;
            color: white;
            text-decoration: none;
        }
        #active{
            background-color: #515d63;
        }
        #content{
            position: relative;
            background-color: ghostwhite;
            width: 80%;
            height: 85%;
            float: left;
            padding: 2%;
        }
    </style>
</head>
    <div id = "header">
        <?php
            if(isset($_SESSION['u_id'])){
                echo "<a href = includes/logout.inc.php>Logout</a>";
            }
            else{
                echo " <a href = SignUp.php>Sign Up</a><a href = Login.php>Login</a>";
            }
        ?>
        <h1 style="text-align: center">SHUTTLE TRANSPORTATION</h1>
        <h5 style="text-align: center">Easily book your shuttle...</h5>

    </div>
