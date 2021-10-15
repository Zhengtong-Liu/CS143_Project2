<html>
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
            $movies[$row['id']] = $row['title'];
        }
        
        print "Search result(s) for Movies: <br>";
        foreach($movies as $mid => $title) {
            print "<a href='http://localhost:8888/movie.php?id=$mid'>$title</a><br>";
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
            $actors[$row['id']] = array($row['first'], $row['last']);
        }
        
        print "Search result(s) for Actors: <br>";
        foreach($actors as $id => $name) {
            print "<a href='http://localhost:8888/actor.php?id=$id'>$name[0] $name[1]</a><br>";
        }
        $rs->free();
        $db -> close();
    }
?>
</body>
</html>