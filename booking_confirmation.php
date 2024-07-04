<?php
session_start();
include('../db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['seats'])) {
    // Process booking and insert into database
    // Display booking confirmation details
} else {
    header("Location: browse_movies.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmation</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
</head>
<body>
    <h1>Booking Confirmation</h1>
    <div class="confirmation">
        <!-- Display booking details -->
        <p>Thank you for your booking!</p>
        <!-- Display booking details such as movie title, showtime, seats, etc. -->
        <p>Booking ID: ABC123</p>
        <p>Movie: Avengers: Endgame</p>
        <p>Showtime: 2024-06-24 19:00</p>
        <p>Seats: A1, A2, A3</p>
    </div>
</body>
</html>
