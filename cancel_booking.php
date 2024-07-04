<?php
session_start();
include('../db.php');

// Fetch and display user's bookings for cancellation
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cancel Booking</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
</head>
<body>
    <h1>Cancel Booking</h1>
    <div class="bookings">
        <!-- Display user's bookings with cancel option -->
        <div class="booking">
            <p>Booking ID: ABC123</p>
            <p>Movie: Avengers: Endgame</p>
            <p>Showtime: 2024-06-24 19:00</p>
            <p>Seats: A1, A2, A3</p>
            <a href="cancel.php?id=ABC123">Cancel</a>
        </div>
    </div>
</body>
</html>
