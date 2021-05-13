<?php
 include "../config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    

<?php

$commentId = $_POST['commentId'];

if (isset($_POST['delete'])) {
    $query = "DELETE FROM `comments` WHERE commentId = $commentId";
    mysqli_query($db, $query);
    echo $query;
    $previousPage = $_SERVER['HTTP_REFERER'];
    header("LOCATION: $previousPage");
}

?>



</body>
</html>

