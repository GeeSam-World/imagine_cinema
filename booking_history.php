<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Booking History - Imagine Cinema</title>
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
.container {
    max-width: 800px;
    margin: 20px auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.booking-item {
    margin-bottom: 20px;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    background-color: #f9f9f9;
}
.booking-item h3 {
    margin: 0;
}
.booking-item p {
    margin: 5px 0;
}
</style>
</head>
<body>
<div class="header">
    <img src="logo.jpg" width="70" height="70">
    <div class="sub"><h1>IMAGINE CINEMA</h1></div>

<!-- <h3>Movie Ticket Reservation System</h3> -->
</div>
<div class="menu">
    <nav>
        <a href="homepage.php">Browse Movies</a>
        <a href="booking_history.php">Booking History</a>
        <a href="logout.php">Logout</a>
</nav>
</div>

<div class="container">
    <h2>Your Booking History</h2>
    <?php
    session_start();
    include_once('db.php');

    // Check if user is logged in
    if (!isset($_SESSION['username'])) {
        // Redirect to login page if not logged in
        header("Location: login.php");
        exit;
    }

    // Retrieve user's bookings
    $username = mysqli_real_escape_string($conn, $_SESSION['username']);
    $query = "SELECT * FROM bookings WHERE username = '$username' ORDER BY booking_time DESC";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='booking-item'>";
            echo "<h3>Booking ID: " . $row['id'] . "</h3>";
            echo "<p>Movie: " . getMovieName($conn, $row['movie_id']) . "</p>";
            echo "<p>Seats: " . $row['seats'] . "</p>";
            echo "<p>Booking Time: " . $row['booking_time'] . "</p>";
            echo "</div>";
        }
    } else {
        echo "<p>No bookings found.</p>";
    }

    // Function to get movie name by movie_id
    function getMovieName($conn, $movieId) {
        $query = "SELECT names FROM movie WHERE movie_id = $movieId";
        $result = mysqli_query($conn, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $movie = mysqli_fetch_assoc($result);
            return $movie['names'];
        } else {
            return "Unknown Movie";
        }
    }
    ?>
</div>

</body>
</html>
