<?php
session_start();
include('../db.php');

$movies = $conn->query("SELECT * FROM movies");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Browse Movies</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
</head>
<body>
    <h1>Browse Movies</h1>
    <div class="movies">
        <?php while($movie = $movies->fetch_assoc()): ?>
        <div class="movie">
            <a href="view_movie.php?id=<?php echo $movie['id']; ?>">
                <img src="../assets/images/<?php echo $movie['image']; ?>" alt="<?php echo $movie['title']; ?>">
                <h2><?php echo $movie['title']; ?></h2>
                <p><?php echo $movie['description']; ?></p>
                <p>Duration: <?php echo $movie['duration']; ?> minutes</p>
            </a>
        </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
