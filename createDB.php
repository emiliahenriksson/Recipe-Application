
<?php 

$host = "localhost";
$user = "root";
$password = "root";

$conn = new mysqli($host, $user, $password);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

$db = "CREATE DATABASE recipeDB";
if ($conn->query($db) === TRUE) {
    echo "Database created successfully";
  } else {
    echo "Error creating database: " . $conn->error;
  }
  $conn->close();

  
$host = "localhost";
$user = "root";
$password = "root";
$database = "recipeDB";

$conn = mysqli_connect($host, $user, $password, $database);
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

$tableUser = "CREATE TABLE IF NOT EXISTS User (
    userId INT(4) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL,
    password VARCHAR(30) NOT NULL,
    UNIQUE (username)
)";

$tableRecipes = "CREATE TABLE IF NOT EXISTS Recipes (
    recipeId INT(4) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(50) NOT NULL,
    ingredients VARCHAR(600) NOT NULL,
    instructions VARCHAR(2000) NOT NULL,
    image VARCHAR(100) NOT NULL,
    authorId INT(4) NOT NULL, 
    FOREIGN KEY (authorId) REFERENCES User(userID)
)";

$tableUserRecipes = "CREATE TABLE IF NOT EXISTS UserRecipes (
    userID INT(4),
    recipeId INT(4),
    recipeIdAPI INT(6),
    FOREIGN KEY (userId) REFERENCES User(userId),
    FOREIGN KEY (recipeId) REFERENCES Recipes(recipeId)
)";

$tableComments = "CREATE TABLE IF NOT EXISTS Comments (
    commentId INT(4) AUTO_INCREMENT PRIMARY KEY,
    authorId INT(4),
    recipeId INT(4),
    recipeIdAPI INT(6),
    comment VARCHAR(100) NOT NULL,
    FOREIGN KEY (authorId) REFERENCES User(userId),
    FOREIGN KEY (recipeId) REFERENCES Recipes(recipeId)
)";

$tables = [$tableUser, $tableRecipes, $tableUserRecipes, $tableComments];

foreach($tables as $table => $db){
    $query = @$conn->query($db);

    if(!$query)
       echo "Table $table : Creation failed ($conn->error)";
    else
       $errors[] = "Table $table : Creation done";
}

$conn->close();

?>