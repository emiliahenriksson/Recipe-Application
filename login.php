<?php
 session_start();
 $day = new DateTime('+1 day');
 setcookie('PHPSESSID', session_id(), $day->getTimeStamp(), '/', null, null, true);

 include "config.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>

<form action='' method='POST' >
    <label for='username'>Username</label>
    <input type='text' name='username' placeholder='Username' value=''>
    <label for='password'>Password</label>
    <input type='password' name='password' placeholder='Password' value=''>
    <input type='submit' name='submit' value='Submit'></input>
</form>
    
<?php

$user = $_SESSION["username"];

if ($_SESSION["username"] != "") {
    echo "You are logged in as $user";
} else {
    echo "You are not logged in";
}

echo $_SESSION["username"];

if(isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * from `user` WHERE `username`= ? AND `password` = ?";

    $stmt = $db->prepare($query);
    $stmt->bind_param('ss', $username, MD5($password));
    $stmt->bind_result($userId, $user, $pw);
    $stmt->execute();
    while ($stmt->fetch()) {

        echo "$user logged in successfully!";

        $_SESSION["username"] = "$user";
        $_SESSION["userId"] = "$userId";
        $_SESSION['userip'] = $_SERVER['REMOTE_ADDR'];

        echo $_SESSION["username"];
        echo $_SESSION['userip'];
        echo $_SERVER['REMOTE_ADDR'];

        
    }

    $stmt->close();
    
}

?>


</body>
</html>