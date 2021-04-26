<?php
 include "config.php";
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Recipes</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    
<div>
    <form action="" method=GET>
        <input type="text" name="searchQuery" placeholder="search recipe" value="">
        <input type="submit"name="search" value="Search">
    </form>
</div>

<?php

if(isset($_GET['search'])) {
    $search = $_GET['searchQuery'];
    
    $query = "SELECT * FROM `recipes` WHERE `title` LIKE '%$search%'";

    //echo $search;

    $stmt = $db->prepare($query);
    $stmt->bind_result($id, $title, $ingredients, $instructions, $img, $author);
    $stmt->execute();

    while($stmt->fetch()) {
        //echo "$img";
        $imgName = "<img src='images/$img' >";
        echo $imgName;
    }

    $stmt->close();

    ?>
    <?php
    
    $curl = curl_init();

    $search = str_replace(' ', '%20', $search);

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://tasty.p.rapidapi.com/recipes/list?from=0&size=50&q=$search",
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
        for ($i = 0; $i <= 50; $i++) {
            //echo $jsonArrayResponse[results][0][thumbnail_url];
            $imgName = $jsonArrayResponse[results][$i][thumbnail_url];
            //echo "<img src='$imgName' >";
            echo "<a href='https://google.com'><img src='$imgName' ></a>";
        }
        
        //echo $jsonArrayResponse[results][0][thumbnail_url];
    }    



}



?>





</body>
</html>