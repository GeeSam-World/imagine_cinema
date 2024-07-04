<?php
session_start();
include_once('db.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Imagine Cinema</title>
<style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}
.menu {
    background-color: #0E2E1D;
    color: white;
    padding: 1rem;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
}
.header {
    background-color: #137C33;
    color: white;
    padding: 1rem;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}
nav a {
    color: white;
    margin: 0 1rem;
    text-decoration: none;
}
nav a:hover {
  background-color: white;
  color: black;
  height: auto;
}
h1 {
    text-align: center;
    margin-bottom: 20px;
}
.movies {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
}
.movie-card {
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    width: 200px;
    overflow: hidden;
    text-align: center;
    cursor: pointer;
}
.movie-image {
    width: 100%;
    height: 300px;
    object-fit: cover;
}
.movie-details {
    padding: 10px;
}
.movie-details h2 {
    font-size: 18px;
    margin: 10px 0 0;
}
.movie-details p {
    font-size: 14px;
    margin: 5px 0;
}
</style>
</head>
<body>
<div class="header">
    <img src="logo.jpg" width="70" height="70">
    <div class="sub"><h1>IMAGINE CINEMA</h1></div>
</div>
<div class="menu">
    <nav>
<a href="index.php">Browse Movies</a>
<?php
if (isset($_SESSION['username'])) {
    echo '<a href="logout.php">Logout</a>';
} else {
    echo '<a href="login.php">Login</a>';
}
?>
<a href="register.php">Register</a>
</nav>
</div>

<div class="movies">
    <?php
    // Query to get movie details along with available seats
    $query = "
    SELECT m.movie_id, m.names, m.showtime, m.filename, m.total_seats - COALESCE(SUM(b.seats), 0) AS available_seats
    FROM movie m
    LEFT JOIN bookings b ON m.movie_id = b.movie_id
    GROUP BY m.movie_id, m.names, m.showtime, m.filename, m.total_seats
    ";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Correct file path
            $imagePath = str_replace("../", "", $row['filename']); // remove ../ to make it relative to the root
            echo "<div class='movie-card'>";
            if (isset($_SESSION['username'])) {
                echo "<a href='homepage.php?movie_id=" . $row['movie_id'] . "'>";
            } else {
                echo "<a href='login.php'>";
            }
            echo "<img src='" . $imagePath . "' alt='" . $row['names'] . "' class='movie-image'>";
            echo "<div class='movie-details'>";
            echo "<h2>" . $row['names'] . "</h2>";
            echo "<p>Showtime: " . $row['showtime'] . "</p>";
            echo "<p>Available Seats: " . $row['available_seats'] . "</p>";
            echo "</div>";
            echo "</a>";
            echo "</div>";
        }
    } else {
        echo "<p>No movies found.</p>";
    }
    ?>
</div>
</body>
</html>
