<html>
<body>
<?php
$db = new mysqli('localhost', 'cs143', '', 'class_db');
if ($db -> connect_errno > 0) {
    die('Unable to connect to database ['. $db -> connect_error .']');
}
$id = $_GET["id"];
$query = "WITH M AS (
            SELECT R.name AS name, R.time AS time, R.mid AS mid, 
                R.rating AS score, R.comment AS comment, M.id AS id,
                M.title AS title, M.year AS year, M.rating AS rating, M.company AS company,
                AVG(R.rating) OVER(PARTITION BY R.mid) AS avg_score
            FROM Review R
            RIGHT OUTER JOIN Movie M
            ON R.mid = M.id
            WHERE M.id = $id
            )
            SELECT * 
            FROM M, MovieActor A, MovieGenre G
            WHERE M.id = A.mid AND G.mid = A.mid
            ";
$rs = $db -> query($query);

if (!rs) {
    $errmsg = $db -> $error;
    print "Query failed: $errmsg <br>";
    exit(1);
}

$info = array();
$genres = array();
$actors = array();
$reviews = array();
$avg_score = array();

while ($row = $rs -> fetch_assoc()) {
    array_push($info, array($row['id'], $row['title'], $row['year'], $row['rating'], $row['company']));
    array_push($genres, $row['genre']);
    $reviews[$row['time']] = array($row['name'], $row['score'], $row['comment']);
    $actors[$row['aid']] = $row['role'];
    array_push($avg_score, $row['avg_score']);
}
$info = array_unique($info)[0];
$avg_score = array_unique($avg_score)[0];
$genres = array_unique($genres);

print "<p>Id: $info[0] <br>
Title: $info[1] <br>
Year: $info[2] <br>
Rating: $info[3] <br>
Company: $info[4] <br>
</p>";
print "Genre: ";
foreach ($genres as $genre) {
    print "$genre ";
}
print "<br>";

print "<p>";
foreach ($actors as $aid => $role) {
    print "<a href='http://localhost:8888/actor.php?id=$aid'>Actor: $aid, Role: $role</a><br>";
}
print "</p>";

print "Average rating from the users: $avg_score/5 <br>";


print "Comments from the users: <br>";
foreach ($reviews as $time  => $review) {
    if (! empty($time)) {
        print "<p>Name:$review[0]<br>
        Time: $time<br>
        Rating score: $review[1]<br>
        Comment: $review[2]<br></p>";
    }
}
print "<br>";

print "<a href='http://localhost:8888/review.php?id=$info[0]'>
    <button>add Comment</button>
</a>";

// print "<br> Total results of query (genres * movies): " . $rs -> num_rows;
$rs -> free();

$db -> close();
?>
</body>  
</html>