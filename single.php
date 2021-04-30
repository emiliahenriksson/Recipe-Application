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

if ($_SESSION["username"] != "") {
    echo "You are logged in as $user";
} else {
    echo "You are not logged in";
}

?>


<form action="" method=POST>
    <input type="submit"name="save" value="save">
</form>

<?php

if (isset($_POST['save'])) {

    $userId = $_SESSION["userId"];

    $query = "SELECT * FROM `userRecipes` WHERE `userId` = '$userId'";

    //echo $search;

    $stmt = $db->prepare($query);
    $stmt->bind_result($uId, $rId, $raId);
    $stmt->execute();

    while($stmt->fetch()) {

        echo $raId;
        echo $_GET['ida'];


        if ($_GET['id']) {
            $recipeId = $_GET['id'];

            if ($uId == $userId && $rId != $recipeId) {
                $query = "INSERT INTO `UserRecipes` (userId, recipeId) VALUES ('$userId', '$recipeId')";
                mysqli_query($db, $query);
            }
            

        } else if ($_GET['ida']) {
            echo "<br> hello1";
            $recipeId = $_GET['ida'];

            if ($uId == $userId && $raId != $recipeId) {
                echo "<br> hello2";
                $query = "INSERT INTO `UserRecipes` (userId, recipeIdAPI) VALUES ('$userId', '$recipeId')";
                mysqli_query($db, $query);                
            }

        }

    }

    $stmt->close();



    

}

?>





<?php

if ($_GET['id']) {

    $recipeId = $_GET['id'];

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

    $recipeId = $_GET['ida'];

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





</body>
</html>