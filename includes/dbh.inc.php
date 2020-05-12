<?php
    $dbServerName = "localhost";
    $dbUserName = "root";
    $dbPassword = "";
    $dbName = "transportsystem";

    // Create connection
    $conn = mysqli_connect($dbServerName, $dbUserName, $dbPassword, $dbName);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $capacity = 4;
    $seg = array();
    $pass = array();

