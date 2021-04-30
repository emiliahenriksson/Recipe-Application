<?php
    session_start();
    include "config.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
</head>
<body>

<?php

$user = $_SESSION["username"];

if ($_SESSION["username"] != "") {
    echo "You are logged in as $user";
    echo $_SESSION['userip'];
    echo $_SERVER['REMOTE_ADDR'];
    if ($_SESSION['userip'] !== $_SERVER['REMOTE_ADDR']) {
        echo "ip adress is not correct";
    }
} else {
    echo "You are not logged in";
}


?>
    
</body>
</html>