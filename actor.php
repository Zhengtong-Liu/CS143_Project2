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
echo "<table>
<tr>
  <th>Id</th><th>Name</th><th>Sex</th><th>Born</th><th>Died</th>
</tr>
<tr>
  <td>$id</td><td>$info[1] $info[2]</td><td>$info[3]</td><td>$info[4]</td><td>$info[5]</td>
</tr>
</table>";

print "Movie(s) that this actor was in: <br>";
echo "<table>
    <tr>
      <th>MovieName</th>
    </tr>";
foreach($movies as $mid => $title)  {
    echo "<tr> 
    <td><a href='http://localhost:8888/movie.php?id=$mid'>$title</a></td>
    </tr>";
    // print "<a href='http://localhost:8888/actor.php?id=$id'>$name[0] $name[1]</a><br>";
}
echo "</table><br>";

// print "<br> Total number of Movies this actor was in: " . $rs->num_rows;
$rs->free();

$db -> close();
print "<a href='http://localhost:8888/'>Back to main page</a><br>";
?>
</body>
</html>