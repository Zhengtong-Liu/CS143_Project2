<html>
<body>
<!-- in the follwing code, the style are from w3schools-->
<style>
    /* Style inputs with type="text", select elements and textareas */
    input[type=text], select, textarea {
    width: 50%; /* Full width */
    padding: 12px; /* Some padding */ 
    border: 1px solid #ccc; /* Gray border */
    border-radius: 4px; /* Rounded borders */
    box-sizing: border-box; /* Make sure that padding and width stays in place */
    margin-top: 6px; /* Add a top margin */
    margin-bottom: 16px; /* Bottom margin */
    resize: vertical /* Allow the user to vertically resize the textarea (not horizontally) */
    }

    /* Style the submit button with a specific background color etc */
    input[type=submit] {
    background-color: #e7e7e7;
    color: black;
    padding: 12px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    }

    /* Add a background color and some padding around the form */
    /* .container {
    border-radius: 5px;
    padding: 20px;
    } */
</style>
<?php

if($mid = $_GET["id"])
{
    print "<div class='container'>
            <form action='review.php' method='post'>
                <label for='mid'>Movie Id: </label><br>
                    <select id='mid' name='mid'>
                        <option value=$mid>$mid</option>
                    </select><br>
                <label for='name'>Name: </label><br> 
                <input type='text' name='name' placeholder='no more than 20 characters' maxlength='20'><br>
                <label id='rating'>Rating(between 1 and 5): </label><br>
                    <select id='rating' name='rating'>
                        <option value='1'>1</option>
                        <option value='2'>2</option>
                        <option value='3'>3</option>
                        <option value='4'>4</option>
                        <option value='5'>5</option>
                    </select><br>
                <label for='comment'>Comment: </label><br>
                    <textarea name='comment' rows='10' cols='30' maxlength='500' placeholder='no more than 500 characters'
                        style='font-family:Arial;'></textarea><br>
                <button type='submit' class='btn btn-default'>Rating it!</button>
            </form>
        </div>";
} else {
    print "<div class='container'>
            <form action='review.php' method='post'>
                <label for='name'>Name: </label><br> 
                <input type='text' name='name' placeholder='no more than 20 characters' maxlength='20'><br>
                <label id='rating'>Rating(between 1 and 5): </label><br>
                    <select id='rating' name='rating'>
                        <option value='1'>1</option>
                        <option value='2'>2</option>
                        <option value='3'>3</option>
                        <option value='4'>4</option>
                        <option value='5'>5</option>
                    </select><br>
                <label for='comment'>Comment: </label><br>
                    <textarea name='comment' rows='10' cols='30' maxlength='500' placeholder='no more than 500 characters'
                        style='font-family:Arial;'></textarea><br>
                <button type='submit' class='btn btn-default'>Rating it!</button>
            </form>
        </div>";
}


$mid = $_POST["mid"];
$name = $_POST["name"];
$rating = $_POST["rating"];
$comment = $_POST["comment"];

if ($mid) {
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
print "<br><a href='index.php'>Back to main page</a><br>";
?>
</body>
</html>