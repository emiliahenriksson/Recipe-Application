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
    <link rel="stylesheet" href="styles.css">

</head>
<body>

<?php

$user = $_SESSION["username"];
$userId = $_SESSION["userId"];

if ($_SESSION["username"] != "") {
    echo "You are logged in as $user";
    echo $_SESSION['userip'];
    echo $_SERVER['REMOTE_ADDR'];
    if ($_SESSION['userip'] !== $_SERVER['REMOTE_ADDR']) {
        echo "ip adress is not correct";
        header("LOCATION: login.php");
    }
} else {
    header("LOCATION: login.php");
    echo "You are not logged in";
}


?>


<?php


$query = "SELECT * FROM `userRecipes` LEFT OUTER JOIN `recipes` ON recipes.recipeId = userRecipes.recipeId WHERE `userID` = $userId";

$stmt = $db->prepare($query);
$stmt->bind_result($userId, $recipeId, $recipeApi, $recipeId, $title, $ingredients, $instructions, $image, $authorId);
$stmt->execute();

    while($stmt->fetch()) {

        if ($recipeApi != null) {
            $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://tasty.p.rapidapi.com/recipes/detail?id=$recipeApi",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "x-rapidapi-host: tasty.p.rapidapi.com",
            "x-rapidapi-key: 7f93a79ef4mshff235a3dc02cacap146162jsn4849d7364788"
        ],
    ]);

    $response = curl_exec($curl);
    $jsonArrayResponse = json_decode($response, true);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {

        //image
        $imgName = $jsonArrayResponse[thumbnail_url];
        echo "<a href='single.php?ida=$recipeApi'><img src='$imgName' ></a>";
            echo "<form action='includes/deleteFave.php' method='POST'>";
                echo "<input type='hidden' name='recipeApi' value='$recipeApi'>";
                echo "<input type='submit' value='delete' name='delete'>";
            echo "</form>";

    }  
        } else if ($recipeId != null) {

            $imgName = "<a href='single.php?id=$recipeId'><img src='images/$image' ></a>";
            echo $imgName;

            echo "<form action='includes/deleteFave.php' method='POST'>";
                echo "<input type='hidden' name='recipeId' value='$recipeId'>";
                echo "<input type='submit' value='delete' name='delete'>";
            echo "</form>";

        }

        echo "<br>$recipeId";
    }

$stmt->close();

?>
    
</body>
</html>