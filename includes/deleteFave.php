<?php
session_start();
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

$userId = $_SESSION["userId"];
$recipeId = $_POST['recipeId'];
$recipeApi = $_POST['recipeApi'];

echo $userId;

if (isset($_POST['delete'])) {
    if ($recipeId != null) {
        $query = "DELETE FROM `userRecipes` WHERE userId = $userId AND recipeId = $recipeId";
        mysqli_query($db, $query);
        echo $query;
        $previousPage = $_SERVER['HTTP_REFERER'];
        header("LOCATION: $previousPage");        
    }   else if ($recipeApi != null) {
        $query = "DELETE FROM `userRecipes` WHERE userId = $userId AND recipeIdApi = $recipeApi";
        mysqli_query($db, $query);
        echo $query;
        $previousPage = $_SERVER['HTTP_REFERER'];
        header("LOCATION: $previousPage");        
    }

}

?>



</body>
</html>