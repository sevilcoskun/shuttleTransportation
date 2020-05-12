<?php

session_start();

if(isset($_POST['submit'])){

    include_once 'dbh.inc.php';

    $u_email = mysqli_real_escape_string($conn, $_POST['u_email']);
    $u_name = mysqli_real_escape_string($conn, $_POST['u_name']);
    $u_password = mysqli_real_escape_string($conn, $_POST['u_password']);

    //Error handler
    if(empty($u_email) || empty($u_name) || empty($u_password)){
        header("refresh:5;url=../signUp.php?signUp=empty");
        echo 'You need to fill all fields';
        echo '<br>';
        echo 'You are redirecting to the same page...';
        exit();
    }
    else{
        //Check the password requirements are correct
        if((preg_match( "/[a-z]/", $u_password )  && preg_match( "/[0-9]/", $u_password )) ||
            (preg_match( "/[a-z]/", $u_password) && preg_match( "/[A-Z]/", $u_password ) ) ){
            $sql = "SELECT * FROM users wHERE u_name = '$u_name'";
            $result = mysqli_query($conn, $sql);
            $resultCheck = mysqli_num_rows($result);
            if($resultCheck > 0){
                header("refresh:5;url=../signUp.php?signUp=usernametaken");
                echo 'That user name has already taken';
                echo '<br>';
                echo 'You are redirecting to the same page...';

                exit();
            }
            else{
                $hashedPassword = password_hash($u_password, PASSWORD_DEFAULT);
                //Insert data into db
                $sql = "INSERT INTO users (u_name, u_password, u_email) VALUES ('$u_name','$hashedPassword','$u_email');";
                mysqli_query($conn, $sql);
                header("refresh:5;url=../Index.php?signup=success");
                echo 'Successfully signup';
                echo '<br>';
                echo 'You are redirecting to your personal page...';
                exit();
            }
        }
        else{
            header("refresh:5;url=../SignUp.php?SignUp=error");
            echo 'Error, your password is not okay';
            echo '<br>';
            echo 'You are redirecting the same page...';
        }
    }

} else{
        header("refresh:5;url=../SignUp.php?SignUp=error");
        exit();
    }