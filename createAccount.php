<?php
 include "config.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
</head>
<body>
    
<form action="" method="POST">
    <input type='text' name='username' placeholder='Username' value=''>
    <input type='password' name='password' placeholder='Password' value=''>
    <input type='submit' name='submit' value='Submit'></input>
</form>

<?php

if(isset($_POST['username']) && $_POST['password']) {

    $username = $_POST['username'];
    $password = $_POST['password'];
    echo MD5($password);
    $password = MD5($password);

    $query = "INSERT INTO `user` (username, password) VALUES ('$username', '$password')";

    mysqli_query($db, $query);

}


?>



</body>
</html>