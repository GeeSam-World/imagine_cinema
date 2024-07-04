<?php
session_start();
include('../db.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['movie_id'])) {
    $movie_id = $_GET['movie_id'];
    $sql = "SELECT * FROM schedules WHERE movie_id='$movie_id'";
    $result = $conn->query($sql);
} else {
    header("Location: browse_movies.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Select Showtime</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
</head>
<body>
    <h1>Select Showtime</h1>
    <div class="showtimes">
        <?php while($schedule = $result->fetch_assoc()): ?>
        <div class="showtime">
            <a href="select_seat.php?schedule_id=<?php echo $schedule['id']; ?>">
                <p><?php echo $schedule['schedule_time']; ?></p>
            </a>
        </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
