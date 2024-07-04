<?php
session_start();
include('../db.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM movies WHERE id='$id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $movie = $result->fetch_assoc();
    } else {
        header("Location: browse_movies.php");
    }
} else {
    header("Location: browse_movies.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $movie['title']; ?></title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
</head>
<body>
    <h1><?php echo $movie['title']; ?></h1>
    <div class="movie-details">
        <img src="../assets/images/<?php echo $movie['image']; ?>" alt="<?php echo $movie['title']; ?>">
        <p><?php echo $movie['description']; ?></p>
        <p>Duration: <?php echo $movie['duration']; ?> minutes</p>
        <a href="select_showtime.php?movie_id=<?php echo $movie['id']; ?>">Select Showtime</a>
    </div>
</body>
</html>
