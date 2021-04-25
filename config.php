<?php


    $host = "localhost";
    $user = "root";
    $password = "root";
    $database = "recipeDB";


    $db = mysqli_connect($host, $user, $password, $database);
    if($db->connect_errno) {
        echo "Failed to connect to database: (" . $db->connect_errno . ")" . $db->connect_error;
        exit();
    }





?>