<html>
<!-- in the follwing code, the style are from w3schools-->
<style>
    table {
      font-family: arial, sans-serif;
      border-collapse: collapse;
      width: 100%;
    }td, th {
      border: 1px solid #dddddd;
      text-align: left;
      padding: 8px;
    }tr:nth-child(even) {
      background-color: #dddddd;
    } 
</style>
<body>
<?php
print "<a href='index.php'>Back to main page</a><br>";
$db = new mysqli('localhost', 'cs143', '', 'class_db');
if ($db -> connect_errno > 0) {
    die('Unable to connect to database ['. $db -> connect_error .']');
}
$id = $_GET["id"];
$query = "WITH M AS (
            SELECT name, time, mid, 
                R.rating AS score, comment, M.id AS mmid,
                title, year, M.rating AS rating, company,
                AVG(R.rating) OVER(PARTITION BY mid) AS avg_score
            FROM Review R
            RIGHT OUTER JOIN Movie M
            ON R.mid = M.id
            WHERE M.id = $id
            )
            SELECT * 
            FROM M, MovieActor A, MovieGenre G, Actor AC
            WHERE M.mmid = A.mid AND G.mid = A.mid AND AC.id = A.aid
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
    array_push($info, array($row['mmid'], $row['title'], $row['year'], $row['rating'], $row['company']));
    array_push($genres, $row['genre']);
    $reviews[$row['time']] = array($row['name'], $row['score'], $row['comment']);
    $actors[$row['aid']] = array($row['last'],$row['first'],$row['role']);
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

if (count($actors) < 1)
{
    print "We did not find any actors for the movie: $$info[1] <br>" ;
}
else
{
    echo "Actors in this movie:<br>";
    echo "<table>
    <tr>
      <th>ID</th><th>Name</th><th>Role</th>
    </tr>";
    foreach ($actors as $aid => $namerole) {
        echo "<tr> 
        <td><a href='actor.php?id=$aid'>$aid</a></td>
        <td><a href='actor.php?id=$aid'>$namerole[1] $namerole[0]</a></td>
        <td>$namerole[2]</td>
        </tr>";
        // print "<a href='http://localhost:8888/actor.php?id=$id'>$name[0] $name[1]</a><br>";
    }
    echo "</table><br>";
}
print "</p>";



print "Comments from the users: <br>";
foreach ($reviews as $time  => $review) {
    if (! empty($time)) {
        print "<p>Name:$review[0]<br>
        Time: $time<br>
        Rating score: $review[1]<br>
        Comment: $review[2]<br></p>";
    }
    else
    {
        print "Empty comment! <br>";
    }
}
if ($avg_score == NULL)
{
    print "We don't have ratings for this movie yet!<br>";
}
else
{
    print "Average rating from the users: $avg_score/5 <br>";
}
print "<br>";

print "<a href='review.php?id=$info[0]'>
    <button>add Comment</button>
</a>";

// print "<br> Total results of query (genres * movies): " . $rs -> num_rows;
$rs -> free();

$db -> close();
?>
</body>  
</html>