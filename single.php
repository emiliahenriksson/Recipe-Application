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
    <title>Single Recipe</title>
</head>
<body>
    
<?php

$user = $_SESSION["username"];
$userId = $_SESSION["userId"];

if ($_SESSION["username"] != "") {
    echo "You are logged in as $user";

    /**
     * Save recipe
     */
    echo "<form action='' method='POST'>";
        echo "<input type='submit' name='save' value='save'>";
    echo "</form>";

    /**
     * Comments
     */
    echo "<form action='' method='POST'>";
        echo "<input type='text' name='comment' id='comment' placeholder='comment'>";
        echo "<input type='submit' name='submit' value='submit'>";
    echo "</form>";

} else {
    echo "You are not logged in";
}

?>


<?php

if ($_GET['id']) {
    $recipeId = $_GET['id'];
} else if ($_GET['ida']) {
    $recipeId = $_GET['ida'];
}

?>


<?php

if (isset($_POST['submit'])) {
    if ($_POST['comment'] != "") {
        echo "working";

        $userId = $_SESSION["userId"];
        $comment = $_POST['comment'];

        if ($_GET['id']) {
            $query = "INSERT INTO `comments` ( authorId, recipeId, comment ) VALUES ( '$userId', '$recipeId', '$comment' )";
            mysqli_query($db, $query);

        } else if ($_GET['ida']) {
            $query = "INSERT INTO `comments` ( authorId, recipeIdAPI, comment ) VALUES ( '$userId', '$recipeId', '$comment' )";
            mysqli_query($db, $query);
        }
    }
}

?>



<?php

if (isset($_POST['save'])) {

    $userId = $_SESSION["userId"];

    $query = "SELECT * FROM `userRecipes` WHERE `userId` = '$userId'";

    $stmt = $db->prepare($query);
    $stmt->bind_result($uId, $rId, $raId);
    $stmt->execute();

    while($stmt->fetch()) {

        if ($_GET['id']) {

            if ($uId == $userId && $rId == $recipeId) {
                    $duplicate = "false";
            }

        } else if ($_GET['ida']) {
            if ($uId == $userId && $raId == $recipeId): 
                $duplicate = "false";
            endif;
        }
    }

    $stmt->close();

    if ($_GET['id'] && $duplicate != "false") {
        $query = "INSERT INTO `UserRecipes` (userId, recipeId) VALUES ('$userId', '$recipeId')";
        mysqli_query($db, $query);
    }
    else if ($_GET['ida'] && $duplicate != "false") {
        $query = "INSERT INTO `UserRecipes` (userId, recipeIdAPI) VALUES ('$userId', '$recipeId')";
        mysqli_query($db, $query);
    }

    

}

?>



<?php

if ($_GET['id']) {
    $query = "SELECT commentId, authorId, recipeId, comment FROM `comments` WHERE recipeId = $recipeId";
} else if ($_GET['ida']) {
    $query = "SELECT commentId, authorId, recipeIdAPI, comment FROM `comments` WHERE recipeIdAPI = $recipeId";
}

$stmt = $db->prepare($query);
$stmt->bind_result($commentId, $authorId, $recipeId, $comment);
$stmt->execute();

while($stmt->fetch()) {
    echo "<br><br>$comment";

    $link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]?delete=delete";
    echo $link;

    if ($authorId == $userId) {
        echo "<form action='includes/deleteComment.php' method='POST'>";
            echo "<input type='hidden' name='commentId' value='$commentId'>";
            echo "<input type='submit' value='delete' name='delete'>";
        echo "</form>";
    }

}

//$stmt-close();



?>




<?php

if ($_GET['id']) {

    $query = "SELECT * FROM `recipes` JOIN `user` ON recipes.authorId = user.userId WHERE `recipeId` = $recipeId";

    $stmt = $db->prepare($query);
    $stmt->bind_result($id, $title, $ingredients, $instructions, $img, $author, $userId, $name, $pwd);
    $stmt->execute();

    while($stmt->fetch()) {

        //bild och titel
        $imgName = "<a href='https://google.com'><img src='images/$img' ></a>";
        echo $imgName;

        echo $title;
        echo "<br>";

        //ingredienser och instruktioner med line break
        echo nl2br($ingredients);
        echo nl2br($instructions);

        echo "<br>";

        //author (= receptförfattare)
        echo $name;
    }

    $stmt->close();


}



if ($_GET['ida']) {

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://tasty.p.rapidapi.com/recipes/detail?id=$recipeId",
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
        echo "<img src='$imgName' >";

        //instructions
        foreach ($jsonArrayResponse[instructions] as $i) {
            echo $i[display_text];
            echo '<br>';
        }

        //title
        $recipeTitle = $jsonArrayResponse[name];
        echo $recipeTitle;

        //ingredients
        foreach($jsonArrayResponse[sections] as $i) {
            foreach($i[components] as $a) {
                echo $a[raw_text];
                echo "<br>";
            }
        }
    }  
}

?>

<?php

if (isset($_DELETE['delete'])) {
    header("LOCATION: http://www.google.com");
    echo "<br><br>hello";
    $query = "DELETE FROM `comments` WHERE commentId = $commentId";
}

?>


</body>
</html>