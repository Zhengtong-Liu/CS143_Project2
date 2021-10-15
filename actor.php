<html>
<body>

<?php 


$db = new mysqli('localhost', 'cs143', '', 'class_db');
if ($db->connect_errno > 0) { 
    die('Unable to connect to database [' . $db->connect_error . ']'); 
}
$id = $_GET["id"];
$rs = $db -> query("WITH M AS (
                        SELECT * 
                        FROM MovieActor M1, Movie M2
                        WHERE M1.mid = M2.id
                    )
                    SELECT * 
                    FROM Actor A
                    LEFT OUTER JOIN M
                    ON A.id = M.aid
                    WHERE A.id = $id
                    ");
if (!$rs) {
    print "Query failed: $db -> error <br>";
    exit(1);
}

$info = array();
$movies = array();

while ($row = $rs->fetch_assoc()) {
    array_push($info, array($row['id'], $row['last'], $row['first'], $row['sex'], $row['dob'], $row['dod']));
    $movies[$row['mid']] = $row['title'];
}

$info = array_unique($info)[0];

print "<p>Id: $id <br> 
        Name: $info[1] $info[2] <br>
        Sex: $info[3] <br>
        Born: $info[4] <br>
        Died: $info[5] <br></p>";

print "Movie(s) that this actor was in: <br>";
foreach($movies as $mid => $title) {
    print "<a href='http://localhost:8888/movie.php?id=$mid'>$title</a><br>";
}

// print "<br> Total number of Movies this actor was in: " . $rs->num_rows;
$rs->free();

$db -> close();
?>
</body>
</html>