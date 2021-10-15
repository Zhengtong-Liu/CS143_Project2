<html>
<body>
<?php

$mid = $_GET["id"];
print "<form action='review.php' method='get'>
Movie Id: <input type='number' name='id' value=$mid><br>
Name: <input type='text' name='name'><br>
Rating(between 1 and 5): <input type='number' name='rating' min='1', max='5'><br>
Comment: <input type='text' name='comment'><br>
<input type='submit' name='submit'>
</form>";

$mid = $_GET["id"];
$name = $_GET["name"];
$rating = $_GET["rating"];
$comment = $_GET["comment"];

if (isset($_GET["submit"])) {
    $db = new mysqli('localhost', 'cs143', '', 'class_db');
    if ($db -> connect_errno > 0) {
        die('Unable to connect to database ['. $db -> connect_error .']');
    }
    $statement = $db -> prepare("INSERT INTO Review VALUES (?, CURRENT_TIMESTAMP, ?, ?, ?)");
    $statement -> bind_param('siis', $name, $mid, $rating, $comment);
    $statement -> execute();

    echo 'Here is the information that is successfully added <br> mid: ' . $mid . '<br> name: ' . $name . '<br> rating: ' . 
    $rating . '<br> comment: ' . $comment . '<br>';

    $statement -> close();
    $db -> close();
}
?>
</body>
</html>