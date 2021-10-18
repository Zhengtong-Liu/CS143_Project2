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

<script type="text/javascript">
function submit_movie()
{
document.forms["submit_movie"].submit();
}
function submit_actor()
{
document.forms["submit_actor"].submit();
}
</script>

<form id="submit_movie", action="search.php", method="get">
Movie title: <input type="text" name="movie">
<a href="javascript: submit_movie()"><button>Search Movie!</button></a>
</form>
<form id="submit_actor", action="search.php", method="get">
Actor name: <input type="text" name="actor">
<a href="javascript: submit_actor()"><button>Search Actor!</button></a>
</form>
<a href='./index.php'>Back to main page</a><br>
<?php
    if (isset($_GET["movie"])) {
        $db = new mysqli('localhost', 'cs143', '', 'class_db');
        if ($db -> connect_errno > 0) {
            die('Unable to connect to database ['. $db -> connect_error .']');
        }
        $movie_names = explode(" ", $_GET['movie']);
        $clauses = array();
        foreach ($movie_names as $movie_name) {
            array_push($clauses, "(title like '%$movie_name%')");
        }
        $clause = implode(' AND ', $clauses);
        // print $clause;
        $rs = $db -> query("SELECT * FROM Movie WHERE ($clause)");
        if (!$rs) {
            print "Query failed: $db -> error <br>";
            exit(1);
        }
        $movies = array();
        while ($row = $rs->fetch_assoc()) {
            $movies[$row['id']] = array($row['title'], $row['year']);
        }
        
        if (count($movies) < 1)
        {
            print "We didn't find any Movie that has similar name: $movie_name. Please double check! <br>";
        }
        else
        {
            print "Search result(s) for Movies: <br>";
            echo "<table>
            <tr>
              <th>Movie Title</th><th>Year</th>
            </tr>";
            foreach($movies as $mid => $title) {
                echo "<tr> 
                <td><a href='http://localhost:8888/movie.php?id=$mid'>$title[0]</a></td>
                <td><a href='http://localhost:8888/movie.php?id=$mid'>$title[1]</a></td>
                </tr>";
                // print "<a href='http://localhost:8888/actor.php?id=$id'>$name[0] $name[1]</a><br>";
            }
            echo "</table><br>";
        }
        $rs->free();
        $db -> close();
    }
    if (isset($_GET["actor"])) {
        $db = new mysqli('localhost', 'cs143', '', 'class_db');
        if ($db -> connect_errno > 0) {
            die('Unable to connect to database ['. $db -> connect_error .']');
        }
        $actor_names = explode(" ", $_GET['actor']);
        // print_r($actor_names);
        $clauses = array();
        foreach ($actor_names as $actor_name) {
            array_push($clauses, "((last like '%$actor_name%') OR (first like '%$actor_name%'))");
        }
        $clause = implode(' AND ', $clauses);
        // print $clause;
        $rs = $db -> query("SELECT * FROM Actor WHERE ($clause)");
        if (!$rs) {
            print "Query failed: $db -> error <br>";
            exit(1);
        }
        $actors = array();
        while ($row = $rs->fetch_assoc()) {
            $actors[$row['id']] = array($row['first'], $row['last'], $row['dob']);
        }
        if (count($actors) < 1)
        {
            print "We didn't find any Actor that has similar lastname or firstname: $actor_name, please double check! <br>";
        }
        else
        {
            $search_actor_name = implode(" ", $actor_names);
            echo "Search result(s) for Actors with name: $search_actor_name <br>";
            echo "<table>
            <tr>
              <th>Name</th><th>Date of Birth</th>
            </tr>";
            foreach($actors as $id => $name) {
                echo "<tr> 
                <td><a href='http://localhost:8888/actor.php?id=$id'>$name[0] $name[1]</a></td>
                <td><a href='http://localhost:8888/actor.php?id=$id'>$name[2]</a></td>
                </tr>";
                // print "<a href='http://localhost:8888/actor.php?id=$id'>$name[0] $name[1]</a><br>";
            }
            echo "</table><br>";
        }
        $rs->free();
        $db -> close();
    }
?>
</body>
</html>