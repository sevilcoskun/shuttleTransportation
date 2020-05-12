<?php

session_start();

if(isset($_POST['submit'])) {

    include_once 'dbh.inc.php';

    $u_email = mysqli_real_escape_string($conn, $_POST['u_email']);
    $u_password = mysqli_real_escape_string($conn, $_POST['u_password']);

    //error handlers
    if(empty($u_email) || empty($u_password)){
        header("refresh:5;url=../Login.php?Login=error");
        echo 'Please enter valid username and password';
        echo '<br>';
        echo 'You are redirecting to the same page...';
        exit();
    }
    else{
        $sql = "SELECT * FROM users WHERE u_email = '$u_email'";
        $result = mysqli_query($conn, $sql);
        $resultCheck = mysqli_num_rows($result);
        if($resultCheck < 1){
            header("refresh:5;url=../Login.php?Login=error");
            echo 'invalid username';
            echo '<br>';
            echo 'You are redirecting to the same page...';
            exit();
        }
        else{
            if($row = mysqli_fetch_assoc($result)){
                $hashedPwdCheck = password_verify($u_password,$row['u_password']);
                if($hashedPwdCheck == false){
                    header("refresh:5;url=../Login.php?Login=error");
                    echo 'wrong password';
                    echo '<br>';
                    echo 'You are redirecting to the same page...';
                    exit();
                }
                elseif($hashedPwdCheck == true){
                    //login users
                    $_SESSION['u_id'] = $row['u_id'];
                    $_SESSION['u_name'] = $row['u_name'];
                    $_SESSION['u_email'] = $row['u_email'];
                    header("refresh:5;url=../index.php?Login=success");
                    echo 'Successfully Logged in';
                    echo '<br>';
                    echo 'You are redirecting to your personal page...';
                    exit();
                }
            }
        }

    }
}else{
    header("refresh:5;url=../Login.php?Login=error");
    exit();
}