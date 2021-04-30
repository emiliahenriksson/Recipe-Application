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
    <title>Logout</title>
</head>
<body>

    <form action="" method=POST>
        <input type="submit"name="logout" value="Logout">
    </form>
    
<?php

if(isset($_POST['logout'])) {
    echo $_SESSION["username"];
    $_SESSION["username"] = "";
    $_SESSION["userId"] = "";
    echo $_SESSION["username"];
    
}

?>



</body>
</html>