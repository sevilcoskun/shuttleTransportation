<?php
        session_start();
        session_unset();
        session_destroy();
        session_write_close();
        setcookie(session_name(),'', 0, '/');
        echo "You are logging out...";
        echo "<br>";
        echo "Redirecting to the home page";
        header("refresh:5;url=../index.php");
        exit();